<?php

declare(strict_types=1);

namespace Acme\Offer;

use Acme\Catalog\Product;

class BuyOneGetOneHalfPrice implements OfferInterface
{
    public function __construct(
        private readonly string $productCode
    ) {}

    /**
     * @param array<int, Product> $items
     */
    public function apply(array $items): float
    {
        $matchingItems = array_filter($items, fn($item) => $item->getCode() === $this->productCode);
        
        if (count($matchingItems) === 0) {
            return 0.0;
        }

        $pairs = (int)floor(count($matchingItems) / 2);
        if ($pairs <= 0) {
            return 0.0;
        }

        $firstItem = reset($matchingItems);
        return $pairs * ($firstItem->getPrice() * 0.5);
    }
}