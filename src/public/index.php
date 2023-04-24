<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Config\ConfigFactory;
use Phalcon\Http\Response\Cookies;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use classes\Time;


$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->registerNamespaces(
    [
        "classes" => APP_PATH . "/assets/",
    ]
);

$loader->register();
// setting the dependencies
$container = new FactoryDefault();
$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);
$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);
$container->set(
    'config',
    function () {
        $filename = APP_PATH . '/etc/config.php';
        $factory = new ConfigFactory();
        return $factory->newInstance('php', $filename);
    }
);
$container->set(
    'db',
    function () {
        return new Mysql($this['config']->db->toArray());
    }
);
$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files);
        $session->start();
        return $session;
    }
);
$container->set(
    'cookies',
    function () {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    }
);
$container->set(
    'time',
    function () {
        return new Time();
    }
);
// injecting dependencies into aur application
$application = new Application($container);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
