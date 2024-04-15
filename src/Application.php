<?php

namespace Mariana\FamilyTree;

use Jenssegers\Blade\Blade;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

/**
 * Клас Application з Singleton для ініціалізації компонентів та запуску додатку
 * @package Mariana\FamilyTree
 */
class Application
{
    private static $instance = null;
    private $blade;
    private $dispatcher;

    private function __construct()
    {
        $this->initializeBlade();
        $this->initializeDatabase();
        $this->setupRoutes();
    }

    public static function getInstance(): Application
    {
        if (self::$instance === null) {
            self::$instance = new Application();
        }
        return self::$instance;
    }

    public function run(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                echo $this->blade->make('pages/404')->render();
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                echo "405 Method Not Allowed";
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                call_user_func($handler, $vars);
                break;
        }
    }

    private function initializeBlade(): void
    {
        $this->blade = new Blade(__DIR__ . '/../views', __DIR__ . '/../cache');
    }

    private function initializeDatabase(): void
    {
        Database::getInstance(); // Singleton Database
    }

    private function setupRoutes(): void
    {
        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) {
            $homeController = new Controllers\HomeController($this->blade);
            $tableController = new Controllers\TableController($this->blade);
            $personController = new Controllers\PersonController($this->blade);

            // Визначення маршрутів
            $r->addRoute('GET', '/', [$homeController, 'index']);
            $r->addRoute('GET', '/home', [$homeController, 'index']);
            $r->addRoute('POST', '/add_post', [$homeController, 'addPost']);
            $r->addRoute('GET', '/delete_post', [$homeController, 'deletePost']);
            $r->addRoute('GET', '/table', [$tableController, 'index']);
            $r->addRoute('GET', '/person', [$personController, 'showPerson']);
            $r->addRoute('GET', '/add_person', [$personController, 'showAddPerson']);
            $r->addRoute('GET', '/edit_person', [$personController, 'showEditPerson']);
            $r->addRoute('POST', '/add_person', [$personController, 'addPerson']);
            $r->addRoute('POST', '/edit_person', [$personController, 'editPerson']);
            $r->addRoute('POST', '/delete_person', [$personController, 'deletePerson']);
        });
    }

    public function __clone() {}
    public function __wakeup() {}
}
