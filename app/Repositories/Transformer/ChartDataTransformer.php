<?php

declare(strict_types = 1);

namespace App\Repositories\Transformer;

use App\Repositories\Dto\Chart\ChartDataList;
use App\Repositories\Dto\Contracts\ChartData;
use App\Repositories\Transformer\Base\BaseChartDataTransformer;
use Carbon\CarbonImmutable;

class ChartDataTransformer extends BaseChartDataTransformer
{
    public function __construct(
        private ChartDataList $chartDataList,
        private CarbonImmutable $startDate,
        private CarbonImmutable $endDate,
    ) {
    }

    public function startDate(): CarbonImmutable
    {
        return $this->startDate;
    }

    public function endDate(): CarbonImmutable
    {
        return $this->endDate;
    }

    public function transform(): array
    {
        $dataSets = [];

        /** @var ChartData $chartData */
        foreach ($this->chartDataList  as $chartData) {
            $dataSets[] = (object) array_merge($this->baseChartData(), [
                'label'           => $chartData->enum()->translate(),
                'borderColor'     => $chartData->enum()->chartColor(),
                'backgroundColor' => $chartData->enum()->chartColor(),
                'data'            => $this->formatData($chartData->data()),
            ]);
        }

        return [
            'chartData' => [
                'labels'   => $this->dateRange(),
                'datasets' => $dataSets,
            ],
            'maxValue' => $this->maxValue(),
        ];
    }
}
