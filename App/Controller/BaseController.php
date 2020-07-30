<?php declare(strict_types = 1);

namespace App\Controller;

use App\Service\DBHandler;
use App\Service\Session;

/**
 * Class BaseController
 *
 * @package App\Controller
 */
class BaseController
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var DBHandler|null
     */
    protected $dbHandler;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * Include view file
     * @param string $view
     * @param array  $data
     */
    public function view(string $view, array $data = []): void
    {
        require_once DOCUMENT_ROOT . '/public/views/' . $view . '.html';
    }

    /**
     * render view file
     * @param string $file
     * @param array  $data
     *
     * @return string
     */
    public function render(string $file, array $data = []): string
    {
        $file = DOCUMENT_ROOT . '/public/views/' . $file . '.html';

        if (is_file($file) && file_exists($file))
        {
            ob_start();
            extract($data);
            include($file);

            $content = ob_get_contents();
            ob_end_clean();
        }
        else
        {
            throw new \RuntimeException(sprintf('Cant find view file %s!', $file));
        }

        return $content;
    }

    /**
     * Gets Session
     *
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * Gets DbHandler
     *
     * @return DBHandler
     */
    public function getDbHandler(): DBHandler
    {
        $this->dbHandler = new DBHandler();

        return $this->dbHandler;
    }
}
