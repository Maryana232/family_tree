<?php

namespace Mariana\FamilyTree\Controllers;

use Jenssegers\Blade\Blade;
use Mariana\FamilyTree\Post;

/**
 * Class HomeController
 * @package Mariana\FamilyTree\Controllers
 * Контролер для головної сторінки
 * Цей контролер відповідає за відображення головної сторінки
 */
class HomeController
{
    protected Blade $blade;

    public function __construct(Blade $blade)
    {
        $this->blade = $blade;
    }

    /**
     * Вивести головну сторінку
     * Ця сторінка повинна виводити заголовок "Головна сторінка"
     * Також потрібно вивести посилання на сторінку /table
     * @throws \Exception
     */
    public function index(): void
    {
        $posts = Post::loadAll();

        echo $this->blade->make('pages/home',
            ['title' => 'Головна сторінка',
            'page' => 'home',
            'posts' => $posts]
        )->render();
    }

    /**
     * Додати новий пост
     * Ця форма повинна відправляти дані методом POST на адресу /add_post
     * Поля форми: заголовок, контент
     * Поля заголовок, контент обов'язкові для заповнення
     * Після успішного додавання поста потрібно перенаправити користувача на сторінку /home
     * @throws \Exception
     */
    public function addPost(): void
    {
        $title = $_POST['title'] ?? null;
        $content = $_POST['content'] ?? null;

        if ($title && $content) {
            $post = new Post(null, $title, $content);
            $post->save();
        }

        header('Location: /home');
    }

    /**
     * Видалити пост
     * Цей метод повинен видаляти пост з бази даних
     * Параметр id повинен бути цілим числом
     * Після успішного видалення поста потрібно перенаправити користувача на сторінку /home
     * @throws \Exception
     */
    public function deletePost(): void
    {
        $id = $_GET['post_id'] ?? null;

        if ($id) {
            Post::delete($id);
        }

        header('Location: /home');
    }
}
