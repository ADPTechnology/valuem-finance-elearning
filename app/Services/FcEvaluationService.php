<?php

namespace App\Services;

use App\Models\DroppableOption;
use App\Models\DynamicAlternative;
use App\Models\DynamicQuestion;
use App\Models\Evaluation;
use App\Models\Exam;
use App\Models\FcStep;
use App\Models\FcStepProgress;


class FcEvaluationService
{
    public function start(FcStepProgress $fcStepProgress, FcStep $step)
    {
        $step->loadRelationShipExam();

        $exam = $step->exams->first();

        if ($fcStepProgress->status == 'pending') {


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

                $fcStepProgress->evaluations()->create([
                    'evaluation_time' => $time,
                    'statement' => $question->statement,
                    'correct_alternatives' => $alt_ids,
                    'points' => 0.00,
                    'question_order' => $key + 1,
                    'question_id' => $question->id,
                ]);
            }

            if ($fcStepProgress->status == 'pending') {
                $fcStepProgress->update([
                    'status' => 'in_progress',
                ]);
            }
        }

        if ($fcStepProgress->status != 'pending') {

            $num_question = getSelectedAnswersStepProgress($fcStepProgress);

            return redirect()->route('aula.forgettingCurve.instances.evaluations.show', [
                'fcStepProgress' => $fcStepProgress,
                'num_question' => $num_question + 1
            ]);
        }

        abort(401);
    }



    public function show(FcStepProgress $fcStepProgress, $num_question)
    {


        $evaluations = $fcStepProgress->evaluations;

        $selected_answers = getSelectedAnswersStepProgress($fcStepProgress);

        $isFinished = $fcStepProgress->status == 'finished' ? true : false;

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

            return view('aula.viewParticipant.forgettingCurve.evaluation.quizEvaluation', [
                'exam' => $exam,
                'num_question' => $num_question - 1,
                'question' => $question,
                'evaluations' => $evaluations,
                'fcStepProgress' => $fcStepProgress,
                'alts_ids' => $alts_ids,
                'options_ids' => $options_ids,
                'alternatives' => $alternatives,
                'droppables' => $droppables,
                'selected_answers' => $selected_answers
            ]);
        }

        return view('aula.viewParticipant.forgettingCurve.evaluation.quizEvaluation', [
            'exam' => $exam,
            'num_question' => $num_question - 1,
            'question' => $question,
            'evaluations' => $evaluations,
            'fcStepProgress' => $fcStepProgress,
            'selected_answers' => $selected_answers
        ]);

    }

    public function update(FcStepProgress $fcStepProgress, Exam $exam, $num_question, $key, $evaluation)
    {
        $fcStepProgress->load('certification');

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
                return redirect()->route('aula.forgettingCurve.instances.evaluations.show', [
                    'fcStepProgress' => $fcStepProgress,
                    'num_question' => $num_question + 1
                ]);
            }
        }

        $total_time = $exam->exam_time - (round($diff_time / 60));

        $score = getScoreFromStep($fcStepProgress);

        if ($total_time > $exam->exam_time) {
            $total_time = $exam->exam_time;
        }

        $fcStepProgress->update([
            'status' => 'finished',
            'score' => $score,
        ]);

        $fcInstance = $fcStepProgress->step->instance;

        $certification = $fcStepProgress->certification;

        return redirect()->route('aula.forgettingCurve.instances.show', ['fcInstance' => $fcInstance, 'certification' => $certification]);

    }



}



