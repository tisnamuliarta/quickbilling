<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array|void
     */
    public function index(Request $request)
    {
        $type = $request->type;

        switch ($type) {
            case 'pie':
                return $this->pieChart();
            case 'bar':
                return $this->barChart();
            case 'line':
                return $this->lineChart();
            case 'doughnut':
                return $this->doughnutChart();
        }
    }

    protected function pieChart()
    {
        return [
            'labels' => ['Vuejs', 'EmberJs', 'ReactJs', 'AngularJs'],
            'datasets' => [
                [
                    'backgroundColor' => ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
                    'data' => [40, 20, 80, 10],
                ],
            ],
        ];
    }

    protected function doughnutChart()
    {
        return [
            'labels' => ['Vuejs', 'EmberJs', 'ReactJs', 'AngularJs'],
            'datasets' => [
                [
                    'backgroundColor' => ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
                    'data' => [40, 20, 80, 10],
                ],
            ],
        ];
    }

    protected function lineChart()
    {
        return [
            'labels' => [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
            ],
            'datasets' => [
                [
                    'label' => 'Data One',
                    'backgroundColor' => '#f87979',
                    'data' => [40, 20, 80, 10, 39, 80, 40],
                ],
            ],
        ];
    }

    protected function barChart()
    {
        return [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'Data One',
                    'backgroundColor' => '#f87979',
                    'data' => [40, 20, 12, 40, 30, 50, 70]
                ]
            ],
        ];
    }
}
