<?php

declare(strict_types = 1);

namespace App\Repositories\Transformer\Base;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

abstract class BaseChartDataTransformer
{
    private array $dateRange;

    private int $maxValue = 0;

    abstract public function startDate(): CarbonImmutable;

    abstract public function endDate(): CarbonImmutable;

    protected function baseChartData(): array
    {
        return [
            'fill'                  => false,
            'lineTension'           => 0.2,
            'pointRadius'           => 1,
            'pointHoverBorderWidth' => 2,
            'pointHitRadius'        => 10,
            'beginAtZero'           => true,
        ];
    }

    protected function dateRange(): array
    {
        if (isset($this->dateRange)) {
            return $this->dateRange;
        }

        $dateRange = CarbonPeriod::create($this->startDate(), '1d', $this->endDate());

        $this->dateRange = array_map(fn (Carbon $date) => $date->format('d.m.Y'), $dateRange->toArray());

        return $this->dateRange;
    }

    protected function formatData(Collection $data): array
    {
        $formatted = [];
        $period    = $this->dateRange();

        foreach ($period as $date) {
            $record = $data->where('date', $date)->first();

            $formatted[] = $record ? $record->cnt : 0;
        }

        $this->maxValue = max([...$formatted, $this->maxValue]);

        return $formatted;
    }

    protected function maxValue(): int
    {
        return $this->maxValue;
    }
}
