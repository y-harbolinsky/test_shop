<?php declare(strict_types = 1);

namespace App\Service;

/**
 * Class Request
 *
 * @package App\Service
 */
class Request
{
    /**
     * @var string
     */
    private $url;

    /**
     * Request constructor.
     */
    function __construct()
    {
        $this->url = $_SERVER['REQUEST_URI'];
    }

    /**
     * Gets Url
     *
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return bool|string
     */
    public function getContent()
    {
        return file_get_contents('php://input');
    }
}
