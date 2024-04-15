<?php

namespace Mariana\FamilyTree;

/**
 * Class Router
 * @package Mariana\FamilyTree
 * Клас для роботи з маршрутами
 * Цей клас відповідає за обробку запитів користувача
 * Він визначає, який контролер потрібно викликати для обробки запиту
 */
class Router
{
    protected array $routes = [];

    /**
     * Додати маршрут
     * @param string $uri
     * @param callable $handler
     */
    public function add(string $uri, callable $handler): void
    {
        $this->routes[$uri] = $handler;
    }

    /**
     * Запустити маршрут
     * @param string $uri
     * @return mixed
     */
    public function run(string $uri): mixed
    {
        if (array_key_exists($uri, $this->routes)) {
            return call_user_func($this->routes[$uri]);
        }

        // Якщо маршрут не знайдено, виводимо повідомлення про помилку
        echo "404 Not Found";
        return null; // Вказуємо, що функція повертає значення null
    }
}
