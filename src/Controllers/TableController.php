<?php

namespace Mariana\FamilyTree\Controllers;

use Jenssegers\Blade\Blade;
use Mariana\FamilyTree\Table;

/**
 * Class TableController
 * @package Mariana\FamilyTree\Controllers
 * Контролер для роботи з таблицею
 * Цей контролер відповідає за відображення таблиці з людьми
 * Також відповідає за пагінацію
 */
class TableController
{
    protected Blade $blade;

    /**
     * @throws \Exception
     * Конструктор
     */
    public function __construct(Blade $blade)
    {
        $this->blade = $blade;
    }

    /**
     * Вивести всіх людей
     * Ця сторінка повинна виводити всіх людей з БД у вигляді таблиці
     * Кожен рядок таблиці повинен містити ім'я, прізвище, стать, дату народження, дату смерті, id батька, id матері
     * Також є кнопки для редагування та видалення кожного рядка
     * При кліку на кнопку редагування потрібно перенаправити користувача на сторінку /edit_person?id={id}
     * При кліку на кнопку видалення потрібно видалити людину з БД та перенаправити користувача на сторінку /table
     * Якщо в БД немає жодної людини, потрібно вивести повідомлення "Немає жодної людини"
     * Також потрібно реалізувати пагінацію
     */
    public function index(): void {
        $currentPage = $_GET['page'] ?? 1;
        $itemsPerPage = 10; // Встановлюємо кількість людей на сторінці

        $totalPeople = count(Table::loadAll());
        $totalPages = ceil($totalPeople / $itemsPerPage);
        $peopleOnPage = Table::paginate($currentPage, $itemsPerPage);

        echo $this->blade->make('pages/table', [
            'title' => 'Таблиця',
            'page' => 'table',
            'peopleOnPage' => $peopleOnPage,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ])->render();
    }
}
