<?php declare(strict_types = 1);

namespace App\Model;

/**
 * Class ShoppingCart
 *
 * @package App\Model
 */
class ShoppingCart
{
    /**
     * @var array
     */
    public $products = [];

    /**
     * @var float
     */
    public $totalSum = 0;

    /**
     * Add product to cart
     * @param array $productData
     */
    public function addProduct(array $productData): void
    {
        $productId = 'product_' . $productData['id'];

        if (isset($this->products[$productId]))
        {
            $this->products[$productId]['quantity'] += $productData['quantity'];
        }
        else
        {
            $this->products[$productId] = $productData;
        }

        $this->calculateSum();
    }

    /**
     * Remove product from cart
     * @param string $productId
     */
    public function removeProduct(string $productId): void
    {
        if (isset($this->products[$productId]))
        {
            unset($this->products[$productId]);
        }

        $this->calculateSum();
    }

    /**
     * Calculate total sum
     */
    public function calculateSum(): void
    {
        if (!empty($this->products))
        {
            $sum = 0;
            foreach ($this->products as $productId => $productData)
            {
                $totalPrice = $this->formatNumber($productData['price'] * $productData['quantity']);
                $this->products[$productId]['totalPrice'] = $totalPrice;
                $sum += (float)$totalPrice;
            }

            $this->totalSum = $this->formatNumber($sum);
        }
    }

    /**
     * @param float $number
     *
     * @return string
     */
    public function formatNumber(float $number): string
    {
        return number_format((float)$number, 2, '.', '');
    }

    /**
     * Gets Products
     *
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function hasProducts(): bool
    {
        return !empty($this->products);
    }

    /**
     * Gets TotalSum
     *
     * @return float
     */
    public function getTotalSum(): float
    {
        return $this->totalSum;
    }
}
