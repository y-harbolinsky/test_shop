<?php declare(strict_types = 1);

namespace App\Controller;

use App\Model\ShoppingCart;

/**
 * Class CartController
 *
 * @package App\Controller
 */
class CartController extends BaseController
{
    /**
     * Show shopping cart action
     */
    public function cart(): void
    {
        $userData = $this->getSession()->getCurrentUserData();

        if ($userData->getCart() && $userData->getCart()->hasProducts())
        {
            echo $this->render('cart/shoppingCart', $userData->getRenderCartParams());
        }
    }

    /**
     * Add product to cart action
     * @param array $params
     */
    public function addProduct(array $params = []): void
    {
        if (!empty($params))
        {
            $id = intval($params['id']) ?? null;
            $title = $params['title'] ?? null;
            $price = floatval($params['price']) ?? null;
            $quantity = intval($params['quantity']) ?? null;
            $userData = $this->getSession()->getCurrentUserData();

            if ($id && $title && $price && $quantity)
            {
                if (!$userData->getCart())
                {
                    $userData->cart = new ShoppingCart();
                }

                $userData->getCart()->addProduct(compact('id', 'title', 'price', 'quantity'));
            }

            echo $this->render('cart/shoppingCart', $userData->getRenderCartParams());
        }
    }

    /**
     * Remove product from cart
     * @param int $id
     */
    public function removeProduct(int $id): void
    {
        if ($id)
        {
            $userData = $this->getSession()->getCurrentUserData();
            $userData->getCart()->removeProduct('product_' . $id);

            echo json_encode([
                'totalSum' => $userData->getCart()->getTotalSum(),
            ]);
        }
    }
}
