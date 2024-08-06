<?php

namespace App\Http\Controllers\Aula\Participant;

use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use App\Models\DroppableOption;
use App\Models\DynamicAlternative;
use App\Models\DynamicQuestion;
use App\Models\Evaluation;
use App\Models\Exam;
use App\Models\FreecourseEvaluation;
use App\Models\ProductCertification;
use App\Models\UserFcEvaluation;
use Auth;
use Illuminate\Http\Request;

class AulaFreeCourseEvaluationController extends Controller
{

    public function index(FreecourseEvaluation $evaluation)
    {
        $evaluation->load('exam');

        $html = view('aula.viewParticipant.freecourses.components._basic_evaluation', compact('evaluation'))->render();

        return response()->json([
            'html' => $html,
            'evaluation' => $evaluation,
        ]);
    }

    public function start(FreecourseEvaluation $evaluation, ProductCertification $productCertification)
    {
        $user = Auth::user();

        if (!$this->availableStart($evaluation)) {
            abort(401);
        }

        $userFcEvaluation = UserFcEvaluation::where('fc_evaluation_id', $evaluation->id)
            ->where('p_certification_id', $productCertification->id)
            ->whereHas('productCertification', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        if (!$userFcEvaluation) {

            $userFcEvaluation = UserFcEvaluation::create([
                'fc_evaluation_id' => $evaluation->id,
                'p_certification_id' => $productCertification->id,
                'status' => 'pending',
            ]);

        }

        $userFcEvaluation->load('evaluations');

        $exam = $evaluation->exam()->first();

        if ($userFcEvaluation->status == 'pending') {

            $questions = $exam->questions()->where('active', 'S')->inRandomOrder()->take($exam->questions_count)->get();

            $time = time();

            foreach ($questions as $key => $question) {
                $correct_alternatives = (getCorrectAltFromQuestion($question))->shuffle();

                $alt_ids = "";

                foreach ($correct_alternatives as $i => $alternative) {
                    if ($i > 0) {
                        $alt_ids .= ",";
                    }

                    $alt_ids .= $alternative->id;
                }

                if ($question->question_type_id == 5) {
                    $alt_ids .= ":";

                    $droppable_options = (getDroppableOptionsFromQuestion($question))->shuffle();

                    foreach ($droppable_options as $j => $droppable_option) {
                        if ($j > 0) {
                            $alt_ids = $alt_ids . ",";
                        }
                        $alt_ids = $alt_ids . $droppable_option->id;
                    }
                }

                $userFcEvaluation->evaluations()->create([
                    'evaluation_time' => $time,
                    'statement' => $question->statement,
                    'correct_alternatives' => $alt_ids,
                    'points' => 0.00,
                    'question_order' => $key + 1,
                    'question_id' => $question->id,
                ]);
            }

            if ($userFcEvaluation->status == 'pending') {
                $userFcEvaluation->update([
                    'status' => 'in_progress',
                ]);
            }
        }

        if ($userFcEvaluation->status != 'pending') {

            $num_question = getSelectedAnswersUserFcEvaluation($userFcEvaluation);

            return redirect()->route('aula.freecourse.evaluations.show', [
                'userFcEvaluation' => $userFcEvaluation,
                'num_question' => $num_question + 1
            ]);
        }
        abort(401);
    }

    public function show(UserFcEvaluation $userFcEvaluation, $num_question)
    {
        $evaluations = $userFcEvaluation->evaluations;

        $selected_answers = getSelectedAnswersUserFcEvaluation($userFcEvaluation);

        $isFinished = $userFcEvaluation->status == 'finished' ? true : false;

        if (!isset($evaluations[$num_question - 1]) || ($num_question > $selected_answers + 1) || $isFinished) {
            abort(401, 'Acción no autorizada');
        }

        $question = DynamicQuestion::findOrFail($evaluations[$num_question - 1]->question_id);

        $exam = $question->exam;

        if ($question->question_type_id == 5) {

            $str_options = $evaluations[$num_question - 1]->correct_alternatives;
            $alts_and_options_array = explode(":", $str_options);
            $alts_ids = explode(",", $alts_and_options_array[0]);
            $options_ids = explode(",", $alts_and_options_array[1]);
            $alternatives = DynamicAlternative::whereIn('id', $alts_ids)->with('file')->get(['id', 'description']);
            $droppables = DroppableOption::whereIn('id', $options_ids)->get(['id', 'description']);

            return view('aula.viewParticipant.freecourses.evaluation.quizEvaluation', [
                'exam' => $exam,
                'num_question' => $num_question - 1,
                'question' => $question,
                'evaluations' => $evaluations,
                'userFcEvaluation' => $userFcEvaluation,
                'alts_ids' => $alts_ids,
                'options_ids' => $options_ids,
                'alternatives' => $alternatives,
                'droppables' => $droppables,
                'selected_answers' => $selected_answers
            ]);
        }

        return view('aula.viewParticipant.freecourses.evaluation.quizEvaluation', [
            'exam' => $exam,
            'num_question' => $num_question - 1,
            'question' => $question,
            'evaluations' => $evaluations,
            'userFcEvaluation' => $userFcEvaluation,
            'selected_answers' => $selected_answers
        ]);
    }

    public function update(UserFcEvaluation $userFcEvaluation, Exam $exam, $num_question, $key, $evaluation)
    {

        $userFcEvaluation->load('productCertification', 'productCertification.evaluations');

        $evaluation = Evaluation::findOrFail($evaluation);

        $diff_time = getTimeDifference($evaluation, $exam);

        $its_time_out = getItsTimeOut($diff_time);

        if (!$its_time_out) {

            $question = DynamicQuestion::where('id', $evaluation->question_id)->first();
            $correct_alternatives = getCorrectAltFromQuestion($question);

            $send_alt = null;
            $points = 0;
            $incorrect_points = 0;

            if ($question->question_type_id == 5) {
                $droppable_options_ids = explode(",", (explode(":", $evaluation->correct_alternatives))[1]);

                foreach ($droppable_options_ids as $i => $droppable_option_id) {
                    $droppable_option = DroppableOption::findOrFail($droppable_option_id);

                    $input_value = request('option-' . $droppable_option_id);

                    if ($i > 0 && $send_alt != "" && $input_value != "") {
                        $send_alt = $send_alt . ",";
                    }

                    if ($droppable_option->dynamic_alternative_id == $input_value) {
                        $points += ($question->points / count($correct_alternatives));
                    }

                    if ($input_value != "") {
                        $send_alt = $send_alt . $droppable_option_id . ":" . $input_value;
                    }
                }
            } else {
                $select_alternatives = request('alternative');

                if ($question->question_type_id == 4) {
                    $correct_alt_array = $correct_alternatives->pluck('description')->toArray();

                    foreach ($select_alternatives as $i => $txt_input) {
                        $strClean = strtoupper(trim($txt_input));

                        $alternatives_clean = array_map(function ($x) {
                            return trim(strtoupper($x));
                        }, explode(',', $correct_alt_array[$i]));

                        if (in_array($strClean, $alternatives_clean)) {
                            $points += $question->points / count($correct_alt_array);
                        }
                        if ($i > 0) {
                            $send_alt = $send_alt . ",";
                        }
                        $send_alt = $send_alt . $strClean;
                    }
                } else {
                    $correct_alt_array = $correct_alternatives->pluck('id')->toArray();
                    foreach ($select_alternatives as $i => $alternative_id) {
                        if (in_array($alternative_id, $correct_alt_array)) {
                            $points += ($question->points / count($correct_alt_array));
                        } else {
                            $incorrect_points += ($question->points / count($correct_alt_array));
                        }

                        if ($i > 0) {
                            $send_alt = $send_alt . ",";
                        }
                        $send_alt = $send_alt . $alternative_id;
                    }
                }
            }

            $points = ($points - $incorrect_points) < 0 ? 0 : $points - $incorrect_points;

            $evaluation->update([
                'selected_alternatives' => $send_alt,
                'points' => $points
            ]);

            if ($num_question < $key) {
                return redirect()->route('aula.freecourse.evaluations.show', [
                    'userFcEvaluation' => $userFcEvaluation,
                    'num_question' => $num_question + 1
                ]);
            }
        }

        $total_time = $exam->exam_time - (round($diff_time / 60));

        $score = getScoreFromUserFcEvaluation($userFcEvaluation);

        if ($total_time > $exam->exam_time) {
            $total_time = $exam->exam_time;
        }

        $userFcEvaluation->update([
            'status' => 'finished',
            'points' => $score,
        ]);

        $courseSection = $userFcEvaluation->fcEvaluation->courseSection;
        $course = $courseSection->course;
        $evaluationsCount = $course->courseEvaluations()->count();

        // update product_certification

        $productCertification = $userFcEvaluation->productCertification;
        $evaluationsUserFc = $productCertification->evaluations()->get();
        $scoreEnd = $evaluationsUserFc->sum('points') / $evaluationsCount;

        $flgFinished = $evaluationsUserFc->count() === $evaluationsCount ?  'S' : 'N';

        $productCertification->update([
            'score' => $scoreEnd,
            'flg_finished' => $flgFinished,
        ]);

        // end update product_certification

        // return redirect()->route('aula.freecourse.start', $course);

        $user = Auth::user();

        if ($course->active == 'S') {
            $progress = $user->progressChapters()
                ->wherePivot('last_seen', 1)
                ->whereHas('courseSection', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->select('section_chapters.id', 'section_chapters.section_id')
                ->first();

            if ($progress == null) {
                $current_chapter = $course->courseSections()
                    ->select('id', 'section_order', 'course_id')
                    ->where('section_order', 1)
                    ->with([
                        'sectionChapters' => fn ($query) => $query
                            ->select('id', 'section_id', 'chapter_order')
                            ->where('chapter_order', 1)
                    ])
                    ->first()->sectionChapters->first();

                $verifyProgress = $user->progressChapters()
                    ->where('section_chapter_id', $current_chapter->id)
                    ->first();

                if ($verifyProgress == null) {
                    $user->progressChapters()->attach($current_chapter, [
                        'progress_time' => 0,
                        'last_seen' => 1,
                        'status' => 'P'
                    ]);
                } else {
                    $user->progressChapters()->updateExistingPivot($current_chapter, [
                        'last_seen' => 1,
                    ]);
                }
            } else {
                $current_chapter = $progress;
            }
            return redirect()->route('aula.freecourse.showChapter', [
                'course' => $course,
                'current_chapter' => $current_chapter
            ]);
        } else {
            return redirect()->route('aula.freecourse.index');
        }
    }

    private function availableStart(FreecourseEvaluation $evaluation)
    {
        $evaluation->load([
            'courseSection' => fn ($q) => $q
                ->select(
                    'course_sections.id',
                    'course_sections.course_id'
                    )
                ->with([
                    'sectionChapters' => fn ($q) => $q
                    ->select(
                        'section_chapters.id',
                        'section_chapters.section_id'
                    )
                    ->with([
                            'progressUsers' => fn ($q) => $q
                            ->where('user_id', Auth::user()->id)
                        ]),
                ])
                ->withCount('sectionChapters')
        ]);

        $section = $evaluation->courseSection;

        return $section->section_chapters_count == getFinishedChaptersCountBySection($section);
    }
}
