<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\ProductRating;

class DBHandler
{
    /**
     * @var null|\PDO
     */
    private $dbh;

    /**
     * DBHandler constructor.
     */
    function __construct()
    {
        try
        {
            $this->dbh = DB::getDB();
        }
        catch (\PDOException $exception)
        {
            $this->error = $exception->getMessage();
        }
    }

    /**
     * Get products data
     * @return array
     */
    public function getProducts(): array
    {
        $stmt = $this->dbh->query('SELECT p.*, pr.rating
            FROM product p
            LEFT JOIN (
                SELECT product_id, ROUND(AVG(rating), 2) as rating
                FROM product_rating
                GROUP BY product_id
            )  as pr
            ON p.id = pr.product_id
            ORDER BY p.id ASC;');
        $stmt->setFetchMode(\PDO::FETCH_CLASS, 'App\Entity\Product');

        return $stmt->fetchAll();
    }

    /**
     * Save product rating per user session
     * @param ProductRating $productRating
     */
    public function saveProductRating(ProductRating $productRating): void
    {
        $session = $productRating->getUserSession();
        $productId = $productRating->getProductId();
        $rating = $productRating->getRating();

        $sql = "SELECT * FROM product_rating WHERE user_session = :session_id AND product_id = :product_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam('session_id', $session, \PDO::PARAM_STR);
        $stmt->bindParam('product_id', $productId, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch();

        if (!$data)
        {
            $sql = "INSERT INTO product_rating VALUES (:session_id, :product_id, :rating);";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam('session_id', $session, \PDO::PARAM_STR);
            $stmt->bindParam('product_id', $productId, \PDO::PARAM_INT);
            $stmt->bindParam('rating', $rating, \PDO::PARAM_INT);
            $stmt->execute();
        }
        else
        {
            $sql = "UPDATE product_rating SET rating=:rating WHERE user_session=:session_id AND product_id=:product_id;";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam('session_id', $session, \PDO::PARAM_STR);
            $stmt->bindParam('product_id', $productId, \PDO::PARAM_INT);
            $stmt->bindParam('rating', $rating, \PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}
