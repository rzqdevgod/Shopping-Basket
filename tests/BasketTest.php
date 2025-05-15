<?php

declare(strict_types=1);

namespace Acme\Tests;

use Acme\Basket;
use Acme\Catalog\Product;
use Acme\Delivery\DeliveryCalculator;
use Acme\Offer\BuyOneGetOneHalfPrice;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private Basket $basket;

    protected function setUp(): void
    {
        $catalog = [
            new Product('R01', 'Red Widget', 32.95),
            new Product('G01', 'Green Widget', 24.95),
            new Product('B01', 'Blue Widget', 7.95),
        ];

        $deliveryRules = new DeliveryCalculator([
            90 => 0.00,
            50 => 2.95,
            0 => 4.95
        ]);

        $offers = [
            new BuyOneGetOneHalfPrice('R01')
        ];

        $this->basket = new Basket($catalog, $deliveryRules, $offers);
    }

    /**
     * @dataProvider basketTotalProvider
     * @param array<int, string> $items
     */
    public function testBasketTotal(array $items, float $expectedTotal): void
    {
        foreach ($items as $item) {
            $this->basket->add($item);
        }

        self::assertEquals($expectedTotal, $this->basket->total());
    }

    /**
     * @return array<string, array{array<int, string>, float}>
     */
    public static function basketTotalProvider(): array
    {
        return [
            'B01, G01' => [['B01', 'G01'], 37.85],
            'R01, R01' => [['R01', 'R01'], 54.37],
            'R01, G01' => [['R01', 'G01'], 60.85],
            'B01, B01, R01, R01, R01' => [['B01', 'B01', 'R01', 'R01', 'R01'], 98.27],
        ];
    }
}