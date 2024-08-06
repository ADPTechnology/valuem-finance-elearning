<?php

namespace App\Services\Reports;

use App\Models\{Company, UserSurvey};
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserSurveyService
{
    public function getDataTable(Request $request)
    {
        $query = UserSurvey::with([
            'user',
            'event.user',
            'event.course',
            'survey',
            'surveyAnswers',
            'company'
        ])
            ->where('status', 'finished');

        if ($request->filled('from_date') && $request->filled('end_date')) {
            $query = $query->whereBetween('end_time', [$request->from_date, $request->end_date]);
        }

        $allUserSurveys = DataTables::of($query)
            ->editColumn('end_time', function ($userSurvey) {
                return $userSurvey->end_time;
            })
            ->editColumn('event.user.name', function ($userSurvey) {
                return $userSurvey->event != null ? $userSurvey->event->user->full_name :
                    'No hay registros';
            })
            ->editColumn('event.course.description', function ($userSurvey) {
                return $userSurvey->event != null ? $userSurvey->event->course->description :
                    'No hay registros';
            })
            ->addColumn('action', function ($userSurvey) {
                $btn = '<a href="javascript:void(0)" data-id="' .
                    $userSurvey->id . '" data-original-title="delete" data-url="' . route('admin.surveys.reports.delete', $userSurvey) . '"
                                            class="ms-3 edit btn btn-danger btn-sm
                                            deleteUserSurvey"><i class="fa-solid fa-trash-can"></i></a>';

                return $btn;
            })
            ->make(true);

        return $allUserSurveys;
    }

    public function destroy(UserSurvey $userSurvey)
    {
        if ($userSurvey->surveyAnswers()->detach()) {
            return $userSurvey->delete();
        }

        throw new Exception(config('parameters.exception_message'));
    }



    // ---------- COMPANY ROLE --------------


    // * traer todos los UserSurvey de los eventos donde este asignado el usuario

    public function getSatisfactionSurveysDataTable(Request $request, User $user)
    {

        $participantIds = $user->events()->with('participants')->get()->pluck('participants.*.id')->flatten()->unique()->toArray();

        $query = UserSurvey::with([
            'user',
            'event.user',
            'event.course',
            'survey',
            'surveyAnswers',
            'company'
        ])
            ->where('status', 'finished')
            ->whereIn('user_id', $participantIds)
            ->whereHas('survey', function ($q) {
                $q->whereIn('destined_to', ['evaluation']);
            });

        $allUserSurveys = DataTables::of($query)
            ->editColumn('end_time', function ($userSurvey) {
                return $userSurvey->end_time;
            })
            ->editColumn('event.user.name', function ($userSurvey) {
                return $userSurvey->event->user->full_name ?? 'No hay registros';
            })
            ->editColumn('event.course.description', function ($userSurvey) {
                return $userSurvey->event->course->description ?? 'No hay registros';
            })
            ->make(true);

        return $allUserSurveys;
    }


    public function getByFiltersAndCompany(Request $request, $destined_to = NULL, User $user)
    {
        $participantIds = $user->events()->with('participants')->get()->pluck('participants.*.id')->flatten()->unique()->toArray();

        $surveysQuery = UserSurvey::with([
            'user',
            'survey',
            'surveyAnswers',
            'company',
            'event.user',
            'event.course',
        ])
            ->whereIn('user_id', $participantIds)
            ->withCount('surveyAnswers')
            ->where('status', 'finished')
            ->orderBy('id', 'desc');
        // ->whereHas('company', function ($q2) use ($company) {
        //     $q2->where('companies.id', $company->id);
        // });

        if ($destined_to) {
            $surveysQuery->whereHas('survey', function ($q) use ($destined_to) {
                $q->where('destined_to', $destined_to);
            });
        }

        if ($request->filled('from_date') && $request->filled('end_date')) {
            $surveysQuery->whereBetween('end_time', [$request->from_date, $request->end_date]);
        }

        return $surveysQuery->limit(500)->get();
    }

}
