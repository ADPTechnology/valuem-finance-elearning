<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReportForgettingCurveReport;
use App\Http\Controllers\Controller;
use App\Services\ReportForgettingCurveService;
use Illuminate\Http\Request;

class ReportForgettingCurveController extends Controller
{

    protected $reportForgettingService;

    public function __construct(ReportForgettingCurveService $reportForgettingService)
    {
        $this->reportForgettingService = $reportForgettingService;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            return $this->reportForgettingService->getDataTable();
        }

        return view('admin.reportForgettingCurve.index');
    }

    // EXPORT EXCEL

    public function exportExcel(Request $request)
    {
        $reportForgettingCurveExport = new ReportForgettingCurveReport;

        $forgettingCurves = $this->reportForgettingService->getForgettingCurves();

        $reportForgettingCurveExport->setForgettingCurve($forgettingCurves);

        $date_info = 'últimos_500';

        return $reportForgettingCurveExport->download(
            'reporte-curvas-del-olvido_' . $date_info . '.xlsx'
        );
    }



}
