<?php declare(strict_types = 1);

namespace App\Service;

use App\Model\SessionUser;

/**
 * Class Session
 *
 * @package App\Service
 */
class Session
{
    /**
     * Session constructor.
     */
    function __construct()
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }

        $this->init();
    }

    /**
     * Create new user model and store in session
     */
    public function init(): void
    {
        if (!isset($_SESSION[$this->getSessionId()]))
        {
            $_SESSION[$this->getSessionId()] = new SessionUser();
        }
    }

    /**
     * @return SessionUser
     */
    public function getCurrentUserData(): SessionUser
    {
        return $_SESSION[$this->getSessionId()];
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return session_id();
    }
}
