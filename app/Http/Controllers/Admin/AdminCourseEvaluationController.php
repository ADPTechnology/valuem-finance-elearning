<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreecourseEvaluation;
use Exception;
use Illuminate\Http\Request;

class AdminCourseEvaluationController extends Controller
{
    public function index(FreecourseEvaluation $fcEvaluation)
    {
        $fcEvaluation->load(
            'courseSection.course',
        );

        $course = $fcEvaluation->courseSection->course->loadFreeCourseRelationships();

        return view('admin.free-courses.evaluations.index', compact('fcEvaluation', 'course'));
    }

    public function update(FreecourseEvaluation $fcEvaluation, Request $request)
    {
        $request = normalizeInputStatus($request);

        try {
            $fcEvaluation->update($request->all());
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }

        $message = $success ? getMessageFromSuccess($success, 'updated') : config('parameters.exception_message');

        $html = view('admin.free-courses.partials.components._evaluation_edit', compact('fcEvaluation'))->render();

        return response()->json([
            'html' => $html,
            'success' => $success,
            'message' => $message,
            'title' => $fcEvaluation->title,
            'description' => $fcEvaluation->description ?? '-'
        ]);
    }
}
