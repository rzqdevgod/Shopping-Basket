<?php

declare(strict_types=1);

namespace Acme\Delivery;

class DeliveryCalculator
{
    /** @var array<float, float> */
    private array $sortedRules;

    /**
     * @param array<float, float> $rules
     */
    public function __construct(array $rules)
    {
        $this->sortedRules = $rules;
        krsort($this->sortedRules);
    }

    public function calculate(float $subtotal): float
    {
        foreach ($this->sortedRules as $threshold => $cost) {
            if ($subtotal >= $threshold) {
                return $cost;
            }
        }

        return array_values($this->sortedRules)[0];
    }
}