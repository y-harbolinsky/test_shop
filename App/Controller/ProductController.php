<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\ProductRating;
use App\Service\DBHandler;

/**
 * Class ProductController
 *
 * @package App\Controller
 */
class ProductController extends BaseController
{
    /**
     * Products list action
     */
    public function products(): void
    {
        try
        {
            $handler = new DBHandler();
        }
        catch (\PDOException $exception)
        {
            echo $exception->getMessage();
            exit;
        }

        $this->view('product/products', [
            'products' => $handler->getProducts(),
            'userData' => $this->getSession()->getCurrentUserData(),
        ]);
    }

    /**
     * Save product rating
     * @param int $id
     * @param int $rating
     */
    public function rating(int $id, int $rating = 1): void
    {
        if ($id && $rating)
        {
            $productRating = new ProductRating();
            $productRating
                ->setUserSession($this->getSession()->getSessionId())
                ->setProductId($id)
                ->setRating($rating);

            $this
                ->getDbHandler()
                ->saveProductRating($productRating);
        }
    }
}
