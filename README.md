# Acme Widget Co - Shopping Basket Implementation

A PHP implementation of Acme Widget Co's shopping basket system that handles product catalogs, delivery rules, and special offers.

## Business Requirements

### Products
| Product Name  | Code | Price  |
|--------------|------|--------|
| Red Widget   | R01  | $32.95 |
| Green Widget | G01  | $24.95 |
| Blue Widget  | B01  | $7.95  |

### Delivery Rules
- Orders under $50: $4.95 delivery
- Orders under $90: $2.95 delivery
- Orders $90 or more: Free delivery

### Special Offers
- Buy one Red Widget (R01), get the second half price

## Technical Requirements

- PHP 8.1+
- Composer
- Docker (optional)

## Implementation Details

The basket system implements three main features:
1. Product catalog management
2. Delivery cost calculation based on order total
3. Special offer application

### Basket Interface
```php
class Basket {
    public function __construct(array $catalog, DeliveryCalculator $rules, array $offers);
    public function add(string $productCode): void;
    public function total(): float;
}
```

## Example Usage

```php
$catalog = [
    new Product('R01', 'Red Widget', 32.95),
    new Product('G01', 'Green Widget', 24.95),
    new Product('B01', 'Blue Widget', 7.95)
];

$deliveryRules = new DeliveryCalculator([
    90 => 0.00,
    50 => 2.95,
    0 => 4.95
]);

$offers = [
    new BuyOneGetOneHalfPrice('R01')
];

$basket = new Basket($catalog, $deliveryRules, $offers);
$basket->add('R01');
$basket->add('G01');

echo $basket->total(); // Outputs: 60.85
```

## Test Cases

| Products                    | Total  |
|----------------------------|--------|
| B01, G01                   | $37.85 |
| R01, R01                   | $54.37 |
| R01, G01                   | $60.85 |
| B01, B01, R01, R01, R01    | $98.27 |

## Project Structure

```
src/
  ├── Catalog/
  │   └── Product.php         # Product entity
  ├── Delivery/
  │   └── DeliveryCalculator.php  # Delivery cost calculator
  ├── Offer/
  │   ├── OfferInterface.php      # Offer strategy interface
  │   └── BuyOneGetOneHalfPrice.php  # Specific offer implementation
  └── Basket.php             # Main basket implementation

tests/
  └── BasketTest.php         # Unit and integration tests
```

## Development Setup

1. Clone the repository
```
git clone 
```

2. Install dependencies:
```bash
composer install
```

3. Run tests:
```bash
# Using PHP directly
.\vendor\bin\phpunit

# Using Docker
docker-compose up --build
```

## Quality Assurance

- PHPUnit tests for all business scenarios
- PHPStan Level 8 static analysis
- Docker containerization
- CI/CD pipeline ready

## Assumptions

1. Products are immutable once created
2. Product codes are unique
3. Offers are applied before delivery charges
4. When multiple offers could apply, they are applied in order of definition
5. Negative prices are not allowed
6. All prices are in USD
7. Delivery costs are calculated after offers are applied

## Implementation Notes

- Uses dependency injection for flexible configuration
- Implements Strategy pattern for offers
- Strict typing throughout
- BCMath for precise calculations
- Docker support for consistent environments