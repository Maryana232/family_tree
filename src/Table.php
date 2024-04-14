<?php

namespace Marianna\FamilyTree;

use Exception;
use PDO;

/**
 * Class Table
 * @package Marianna\FamilyTree
 * Клас для роботи з таблицями
 * Цей клас відповідає за роботу з таблицями
 * Він відповідає за завантаження, збереження, видалення та редагування даних в таблицях
 */
class Table {
    /**
     * Завантажити всіх людей
     * @return array
     * @throws Exception
     */
    public static function loadAll(): array {
        $sql = "SELECT * FROM people";
        $stmt = Database::executeQuery($sql);
        $people = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $person = new Person();
            $person->loadFromArray($row);
            $people[] = $person;
        }

        return $people;
    }

    /**
     * Завантажити список чоловіків
     * @return array
     * @throws Exception
     */
    public static function loadMales(): array {
        $sql = "SELECT * FROM people WHERE gender = 'ч'";
        $stmt = Database::executeQuery($sql);
        $males = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $male = new Person();
            $male->loadFromArray($row);
            $males[] = $male;
        }

        return $males;
    }

    /**
     * Завантажити список жінок
     * @return array
     * @throws Exception
     */
    public static function loadFemales(): array {
        $sql = "SELECT * FROM people WHERE gender = 'ж'";
        $stmt = Database::executeQuery($sql);
        $females = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $female = new Person();
            $female->loadFromArray($row);
            $females[] = $female;
        }

        return $females;
    }

    /**
     * Завантажити сторінку з людьми
     * @param int $page
     * @param int $itemsPerPage
     * @return array
     * @throws Exception
     */
    public static function paginate(int $page, int $itemsPerPage): array {
        $start = ($page - 1) * $itemsPerPage;
        $sql = "SELECT * FROM people LIMIT ?, ?";
        $stmt = Database::executeQuery($sql, [$start, $itemsPerPage]);
        $paginatedPeople = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $person = new Person();
            $person->loadFromArray($row);
            $paginatedPeople[] = $person;
        }

        return $paginatedPeople;
    }

    /**
     * Завантажити список всіх, хто молодший за певний вік
     * @param int $age
     * @return array
     * @throws Exception
     */
    public static function loadYoungerThan(int $age): array
    {
        $currentDate = date('Y-m-d');
        $dateLimit = date('Y-m-d', strtotime($currentDate . " - $age years"));
        $sql = "SELECT * FROM people WHERE birth_date > ?";
        $stmt = Database::executeQuery($sql, [$dateLimit]);
        $youngerPeople = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $youngerPeople[] = $row;
        }

        return $youngerPeople;
    }

    /**
     * Завантажити список всіх, хто старший за певний вік
     * @param int $age
     * @return array
     * @throws Exception
     */
    public static function loadOlderThan(int $age): array
    {
        $currentDate = date('Y-m-d');
        $dateLimit = date('Y-m-d', strtotime($currentDate . " - $age years"));
        $sql = "SELECT * FROM people WHERE birth_date <= ?";
        $stmt = Database::executeQuery($sql, [$dateLimit]);
        $olderPeople = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $olderPeople[] = $row;
        }

        return $olderPeople;
    }


}

