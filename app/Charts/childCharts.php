<?php

namespace App\Charts;

use App\Models\ChildRecord;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class childCharts
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        return $this->chart->lineChart()
            ->setTitle('Sales during 2021.')
            ->setSubtitle('Physical sales vs Digital sales.')
            ->addData('Physical sales', [40, 93, 35, 42, 18, 82])
            ->addData('Digital sales', [70, 29, 77, 28, 55, 45])
            ->addLine('hehehhe', ChildRecord::where('children_id', '<=', 60)->get()->toArray())
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);
    }
}
