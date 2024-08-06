<?php

namespace App\Services;

use App\Models\ForgettingCurve;


class CurveInstanceService
{


    public function create(ForgettingCurve $forgettingCurve)
    {
        return $forgettingCurve->instances()->createMany([
            [
                'title' => 'Instancia 1',
                'days_count' => 7,
            ],
            [
                'title' => 'Instancia 2',
                'days_count' => 15,
            ],
        ]);
    }


    public function destroy($instances)
    {
        $instances->delete();
    }

}



