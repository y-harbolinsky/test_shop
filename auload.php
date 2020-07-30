<?php declare(strict_types = 1);

spl_autoload_register(function($className)
{
    $file = str_replace('\\', '/', $className) . '.php';

    if (!file_exists($file))
    {
        var_dump('File ' . $file . ' doesn\'t exists!');
    }

    include_once($file);
});
