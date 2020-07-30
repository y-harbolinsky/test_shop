<?php declare(strict_types = 1);

namespace App\Model;

/**
 * Class SessionUser
 *
 * @package App\Model
 */
class SessionUser
{
    /**
     * @var float
     */
    public $balance = 100;

    /**
     * @var float
     */
    public $remainingBalance = 100;

    /**
     * @var float
     */
    public $lastPurchase = 0;

    /** @var  ShoppingCart|null */
    public $cart = null;

    /**
     * Check there are products in cart
     * @return bool
     */
    public function showCart(): bool
    {
        return $this->cart instanceof ShoppingCart && !empty($this->cart->products);
    }

    /**
     * Gets Cart
     *
     * @return ShoppingCart|null
     */
    public function getCart(): ?ShoppingCart
    {
        return $this->cart;
    }

    /**
     * Get cart products and total sum
     * @return array
     */
    public function getRenderCartParams(): array
    {
        if ($this->getCart())
        {
            return [
                'products' => $this->getCart()->getProducts(),
                'totalSum' => $this->getCart()->getTotalSum(),
            ];
        }

        return [];
    }

    /**
     * Remove cart
     */
    public function clearCart(): void
    {
        $this->cart = null;
    }
}
