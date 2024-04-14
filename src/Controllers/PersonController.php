<?php

namespace Marianna\FamilyTree\Controllers;

use Jenssegers\Blade\Blade;
use Marianna\FamilyTree\Person;
use Marianna\FamilyTree\Table;
use Marianna\FamilyTree\Tree;

/**
 * Class PersonController
 * @package Marianna\FamilyTree\Controllers
 * Контролер для роботи з людьми
 * Цей контролер відповідає за обробку запитів, що стосуються людей
 * Відповідає за відображення сторінок для додавання, редагування, видалення людей
 * Також відповідає за обробку даних, які приходять з форм
 */
class PersonController
{
    protected Blade $blade;

    /**
     * @throws \Exception
     * Конструктор
     */
    public function __construct(Blade $blade) {
        $this->blade = $blade;
    }

    /**
     * @param int $id
     * @return void
     * Вивести сторінку з інформацією про людину
     */
    public function showPerson(): void {
        $id = $_GET['id'] ?? null;  // Отримуємо id з параметрів запиту
        try {
            $person = new Person($id);
        } catch (\Exception $e) {
            echo "Персона не знайдена: " . $e->getMessage();
            return;
        }

        $tree = new Tree($person);

        echo $this->blade->make('pages/person', [
            'title' => 'Персона',
            'person' => $person,
            'tree' => $tree,
        ])->render();
    }

    /**
     * Форма для додавання нової людини
     * Ця форма повинна відправляти дані методом POST на адресу /add_person
     * Поля форми: ім'я, прізвище, стать, дата народження, дата смерті, id батька, id матері
     * Поля ім'я, прізвище, стать, дата народження обов'язкові для заповнення
     * дата смерті, id батька, id матері не обов'язкові
     * id батька, id матері повинні бути цілими числами, потрібно завантажити всіх людей з БД і вивести їх у випадаючому списку
     * Після успішного додавання людини потрібно перенаправити користувача на сторінку /table
     * @throws \Exception
     */
    public function showAddPerson(): void
    {
        echo $this->blade->make('pages/add_person', [
            'title' => 'Додати людину',
            'page' => 'add_person',
            'males' => Table::loadMales(),
            'females' => Table::loadFemales(),
        ])->render();
    }

    /**
     * Форма для редагування існуючої людини
     * Ця форма повинна відправляти дані методом POST на адресу /edit_person
     * Поля форми: ім'я, прізвище, стать, дата народження, дата смерті, id батька, id матері
     * Дані повинні бути попередньо заповнені даними людини
     * Після успішного редагування людини потрібно перенаправити користувача на сторінку /table
     * @throws \Exception
     */
    public function showEditPerson(): void {
        $id = $_GET['person_id'] ?? null;

        if (!$id) {
            // Опрацювання помилки, якщо id не предоставлено
            echo "ID не знайдено";
            return;
        }

        try {
            $person = new Person($id);
        } catch (\Exception $e) {
            // Опрацювання помилки, якщо id не предоставлено
            echo "Персона не знайдена: " . $e->getMessage();
            return;
        }

        echo $this->blade->make('pages/edit_person', [
            'title' => 'Редагувати людину',
            'person' => $person,
            'males' => Table::loadMales(),
            'females' => Table::loadFemales(),
        ])->render();
    }

    /**
     * Додати нову людину
     * Цей метод повинен виконуватися тільки методом POST
     * Після успішного додавання людини потрібно перенаправити користувача на сторінку /table
     * Якщо дані не пройшли валідацію, потрібно перенаправити користувача на сторінку /add_person
     */
    public function addPerson(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validatePersonInput($_POST)) {
                header('Location: /add_person');
                return;
            }
            try{
                $person = new Person();
                $person->create(
                    $_POST['first_name'],
                    $_POST['last_name'],
                    $_POST['gender'],
                    $_POST['birth_date'] ?: null,
                    $_POST['death_date'] ?: null,
                    $_POST['father_id'] ?: null,
                    $_POST['mother_id'] ?: null
                );
            } catch (\Exception $e) {
                echo 'Error: ' . $e->getMessage();
                header('Location: /add_person');
                // Опрацювання помилки
            }
            header('Location: /table');
        }
    }

    /**
     * @throws \Exception
     */
    public function deletePerson(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['person_id_delete'] ?? null;

            if (!$id) {
                echo 'Ви не ввели id';
                return;
            }

            try {
                $person = new Person($id);
                $person->delete();
                header('Location: /tree');
            } catch (\Exception $e) {
                echo 'Error: ' . $e->getMessage();
                // Опрацювання помилки
            }

            header('Location: /table');
        }
    }

    /**
     * @throws \Exception
     * Редагувати існуючу людину
     * Цей метод повинен виконуватися тільки методом POST
     * Після успішного редагування людини потрібно перенаправити користувача на сторінку /table
     * Якщо дані не пройшли валідацію, потрібно перенаправити користувача на сторінку /edit_person
     */
    public function editPerson(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                echo 'Ви не ввели id';
                return;
            }

            try {
                $person = new Person($id);
                $person->update(
                    $_POST['first_name'],
                    $_POST['last_name'],
                    $_POST['gender'],
                    $_POST['birth_date'] ?: null,
                    $_POST['death_date'] ?: null,
                    $_POST['father_id'] ?: null,
                    $_POST['mother_id'] ?: null
                );
            } catch (\Exception $e) {
                echo 'Error: ' . $e->getMessage();
                header('Location: /edit_person?id=' . $id);
            }

            header('Location: /table');
        }
    }

    /**
     * @param array $data
     * @return bool
     * Валідація введених даних
     */
    private function validatePersonInput(array $data): bool
    {
        return !empty($data['first_name']) && !empty($data['last_name']) && !empty($data['birth_date']);
    }
}
