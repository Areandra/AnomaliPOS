<?php

namespace App\Services;

class RestaurantContext
{
    protected ?int $restaurantId = null;

    public function setRestaurantId(?int $id): void
    {
        $this->restaurantId = $id;
    }

    public function getRestaurantId(): ?int
    {
        return $this->restaurantId;
    }

    public function clear(): void
    {
        $this->restaurantId = null;
    }
}
