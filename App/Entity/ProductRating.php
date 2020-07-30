<?php declare(strict_types = 1);

namespace App\Entity;

class ProductRating
{
    /**
     * @var string
     */
    private $user_session;

    /**
     * @var int
     */
    private $product_id;

    /**
     * @var int
     */
    private $rating;

    /**
     * Gets UserSession
     *
     * @return string
     */
    public function getUserSession(): ?string
    {
        return $this->user_session;
    }

    /**
     * Sets UserSession
     *
     * @param string $user_session
     *
     * @return $this
     */
    public function setUserSession(?string $user_session): self
    {
        $this->user_session = $user_session;

        return $this;
    }

    /**
     * Gets ProductId
     *
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * Sets ProductId
     *
     * @param int $product_id
     *
     * @return $this
     */
    public function setProductId(int $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * Gets Rating
     *
     * @return int
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * Sets Rating
     *
     * @param int $rating
     *
     * @return $this
     */
    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
