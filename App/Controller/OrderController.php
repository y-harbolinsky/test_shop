<?php declare(strict_types = 1);

namespace App\Controller;

/**
 * Class OrderController
 *
 * @package App\Controller
 */
class OrderController extends BaseController
{
    public const UPS_SHIPPING = 'ups';
    public const UPS_SHIPPING_COST = 5;
    public const PAYMENT_SUCCESS = 'success';
    public const PAYMENT_FAILURE = 'failure';

    /**
     * Pay action
     * @param string $shippingMethod
     */
    public function pay(string $shippingMethod): void
    {
        $paymentStatus = self::PAYMENT_FAILURE;
        $userData = $this->getSession()->getCurrentUserData();
        $totalPurchase = $userData->getCart()->getTotalSum();

        if ($shippingMethod === self::UPS_SHIPPING)
        {
            $totalPurchase += self::UPS_SHIPPING_COST;
        }

        if ($userData->remainingBalance >= $totalPurchase)
        {
            $userData->lastPurchase = $totalPurchase;
            $userData->balance = $userData->remainingBalance;
            $userData->remainingBalance -= $totalPurchase;
            $userData->remainingBalance = $userData->getCart()->formatNumber($userData->remainingBalance);
            $paymentStatus = self::PAYMENT_SUCCESS;

            $data = [
                'balance' => $userData->balance,
                'lastPurchase' => $userData->lastPurchase,
                'remainingBalance' => $userData->remainingBalance,
                'paymentStatus' => $paymentStatus,
            ];

            $userData->clearCart();
        }
        else
        {
            $data = [
                'balance' => $userData->remainingBalance,
                'totalSum' => $totalPurchase,
                'paymentStatus' => $paymentStatus,
            ];
        }

        echo json_encode([
            'paymentStatus' => $paymentStatus,
            'content' => $this->render('order/pay', $data),
        ]);
    }
}
