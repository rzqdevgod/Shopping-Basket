<?php

declare(strict_types=1);

namespace Acme;

use Acme\Catalog\Product;
use Acme\Delivery\DeliveryCalculator;
use Acme\Offer\OfferInterface;

class Basket
{
    /** @var array<int, Product> */
    private array $items = [];

    /**
     * @param array<int, Product> $catalog
     * @param array<int, OfferInterface> $offers
     */
    public function __construct(
        private readonly array $catalog,
        private readonly DeliveryCalculator $deliveryRules,
        private readonly array $offers = []
    ) {}

    public function add(string $productCode): void
    {
        $product = $this->findProduct($productCode);
        if ($product === null) {
            throw new \InvalidArgumentException("Product not found: {$productCode}");
        }
        $this->items[] = $product;
    }

    public function total(): float
    {
        $subtotal = $this->calculateSubtotal();
        $discount = $this->calculateDiscount();
        $afterDiscount = bcsub((string)(float)$subtotal, (string)(float)$discount, 4);
        $delivery = (string)$this->deliveryRules->calculate((float)$afterDiscount);
        
        $total = bcadd($afterDiscount, $delivery, 4);
        return (float)bcmul($total, '1.00', 2);
    }

    private function calculateSubtotal(): string
    {
        return array_reduce(
            $this->items,
            fn(string $total, Product $item) => bcadd($total, (string)$item->getPrice(), 4),
            '0'
        );
    }

    private function calculateDiscount(): string
    {
        return array_reduce(
            $this->offers,
            fn(string $total, OfferInterface $offer) => bcadd($total, (string)$offer->apply($this->items), 4),
            '0'
        );
    }

    private function findProduct(string $code): ?Product
    {
        foreach ($this->catalog as $product) {
            if ($product->getCode() === $code) {
                return $product;
            }
        }
        return null;
    }
}