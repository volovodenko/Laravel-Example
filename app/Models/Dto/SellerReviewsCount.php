<?php

declare(strict_types = 1);

namespace App\Models\Dto;

class SellerReviewsCount
{
    public function __construct(
        private int $reviewsRating1Count,
        private int $reviewsRating2Count,
        private int $reviewsRating3Count,
        private int $reviewsRating4Count,
        private int $reviewsRating5Count,
    ) {
    }

    public function rating1Count(): int
    {
        return $this->reviewsRating1Count;
    }

    public function rating1CountPercent(): int
    {
        $allCount = $this->allCount();

        return 0 === $allCount ? 0 : (int) round($this->rating1Count() * 100 / $allCount);
    }

    public function rating2Count(): int
    {
        return $this->reviewsRating2Count;
    }

    public function rating2CountPercent(): int
    {
        $allCount = $this->allCount();

        return 0 === $allCount ? 0 : (int) round($this->rating2Count() * 100 / $allCount);
    }

    public function rating3Count(): int
    {
        return $this->reviewsRating3Count;
    }

    public function rating3CountPercent(): int
    {
        $allCount = $this->allCount();

        return 0 === $allCount ? 0 : (int) round($this->rating3Count() * 100 / $allCount);
    }

    public function rating4Count(): int
    {
        return $this->reviewsRating4Count;
    }

    public function rating4CountPercent(): int
    {
        $allCount = $this->allCount();

        return 0 === $allCount ? 0 : (int) round($this->rating4Count() * 100 / $allCount);
    }

    public function rating5Count(): int
    {
        return $this->reviewsRating5Count;
    }

    public function rating5CountPercent(): int
    {
        $allCount = $this->allCount();

        return 0 === $allCount ? 0 : (int) round($this->rating5Count() * 100 / $allCount);
    }

    public function allCount(): int
    {
        return $this->reviewsRating1Count
            + $this->reviewsRating2Count
            + $this->reviewsRating3Count
            + $this->reviewsRating4Count
            + $this->reviewsRating5Count;
    }
}
