<?php

declare(strict_types=1);

namespace Acme\Offer;

use Acme\Catalog\Product;

interface OfferInterface
{
    /**
     * @param array<int, Product> $items
     */
    public function apply(array $items): float;
}