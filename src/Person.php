<?php

namespace Mariana\FamilyTree;

use PDO;

/**
 * Class Person
 * @package Mariana\FamilyTree
 * Клас для роботи з людьми
 * Цей клас відповідає за роботу з людьми
 * Він відповідає за завантаження, збереження, видалення та редагування даних про людину
 */
class Person {
    private ?int $id;
    private string $firstName;
    private string $lastName;
    private string $gender;
    private string $birthDate;
    private ?string $deathDate = null;
    private ?int $fatherId = null;
    private ?int $motherId = null;

    /**
     * @throws \Exception
     */
    public function __construct(?int $id = null) {
        if ($id !== null) {
            $this->load($id);
        }
    }

    /**
     * @throws \Exception
     */
    private function createPerson(int $id): ?Person {
        try {
            return new Person($id);
        } catch (\Exception $e) {
            // Логування помилки
            return null; // Повертаємо null, якщо не вдалося створити об'єкт
        }
    }

    /**
     * @throws \Exception
     */
    private function load(int $id): void {
        $sql = "SELECT * FROM people WHERE id = ?";
        $stmt = Database::executeQuery($sql, [$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw new \Exception("Person not found with ID: $id");
        }

        $this->loadData($data);
    }

    /**
     * @throws \Exception
     */
    public function loadFromArray(array $data): void {
        $this->loadData($data);
    }

    /**
     * @throws \Exception
     */
    protected function loadData(array $data): void
    {
        $this->id = $data['id'];
        $this->firstName = $data['first_name'];
        $this->lastName = $data['last_name'];
        $this->gender = $data['gender'];
        $this->birthDate = $data['birth_date'];
        $this->deathDate = $data['death_date'] ?? null;
        $this->fatherId = $data['father_id'] ?? null;
        $this->motherId = $data['mother_id'] ?? null;
    }

    /**
     * @throws \Exception
     */
    private function executeSql(string $sql, array $params): void {
        Database::executeQuery($sql, $params);
    }

    /**
     * Create a new person.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $gender
     * @param string $birthDate
     * @param string|null $deathDate
     * @param int|null $fatherId
     * @param int|null $motherId
     * @throws \Exception
     */
    public function create(string $firstName, string $lastName, string $gender, string $birthDate, ?string $deathDate, ?int $fatherId, ?int $motherId): void {
        $sql = "INSERT INTO people (first_name, last_name, gender, birth_date, death_date, father_id, mother_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->executeSql($sql, [$firstName, $lastName, $gender, $birthDate, $deathDate, $fatherId, $motherId]);
    }

    /**
     * Update the current person.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $gender
     * @param string $birthDate
     * @param string|null $deathDate
     * @param int|null $fatherId
     * @param int|null $motherId
     * @throws \Exception
     */
    public function update(string $firstName, string $lastName, string $gender, string $birthDate, ?string $deathDate, ?int $fatherId, ?int $motherId): void {
        $sql = "UPDATE people SET first_name = ?, last_name = ?, gender = ?, birth_date = ?, death_date = ?, father_id = ?, mother_id = ? WHERE id = ?";
        $this->executeSql($sql, [$firstName, $lastName, $gender, $birthDate, $deathDate, $fatherId, $motherId, $this->id]);
    }

    /**
     * Delete the current person.
     *
     * @throws \Exception
     */
    public function delete(): void {
        $sql = "DELETE FROM people WHERE id = ?";
        $this->executeSql($sql, [$this->id]);
    }

    /**
     * @return int|null
     */
    public function getFatherId(): ?int {
        return $this->fatherId;
    }

    /**
     * @return int|null
     */
    public function getMotherId(): ?int {
        return $this->motherId;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    /**
     * @return string|null
     */
    public function getDeathDate(): ?string
    {
        return $this->deathDate;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @throws \Exception
     * Методи для отримання інформації про батька
     */
    public function getFather(): ?Person {
        if ($this->fatherId === null) {
            return null;
        }else{
            return $this->createPerson($this->fatherId);
        }
    }

    /**
     * @throws \Exception
     * Методи для отримання інформації про матір
     */
    public function getMother(): ?Person {
        if ($this->motherId === null) {
            return null;
        }else{
            return $this->createPerson($this->motherId);
        }
    }

    /**
     * @throws \Exception
     * Метод для отримання інформації про дітей
     */
    public function getChildren(): array {
        $sql = "SELECT * FROM people WHERE father_id = ? OR mother_id = ?";
        $stmt = Database::executeQuery($sql, [$this->id, $this->id]);
        $children = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $child = new Person();
            $child->loadFromArray($row);
            $children[] = $child;
        }

        return $children;
    }

    /**
     * @throws \Exception
     * Метод для отримання інформації про братів та сестер
     */
    public function getSiblings(): array {
        $sql = "SELECT * FROM people WHERE (father_id = ? OR mother_id = ?) AND id != ?";
        $stmt = Database::executeQuery($sql, [$this->fatherId, $this->motherId, $this->id]);
        $siblings = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sibling = new Person();
            $sibling->loadFromArray($row);
            $siblings[] = $sibling;
        }

        return $siblings;
    }

    /**
     * @throws \Exception
     * Метод для отримання інформації про дідусів та бабусь
     */
    public function getGrandparents(): array {
        // Отримання ідентифікаторів батьків
        $parentIds = [$this->fatherId, $this->motherId];

        // Отримання ідентифікаторів дідусів і бабусь
        $sql = "SELECT father_id, mother_id FROM people WHERE id = ? OR id = ?";
        $stmt = Database::executeQuery($sql, $parentIds);
        $grandparentIds = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $grandparentIds[] = $row['father_id'];
            $grandparentIds[] = $row['mother_id'];
        }

        $grandparentIds = array_unique(array_filter($grandparentIds)); // Фільтрування унікальних та не NULL ідентифікаторів

        if (empty($grandparentIds)) {
            return [];
        }

        // Отримання даних про дідусів та бабусь
        $placeholders = implode(',', array_fill(0, count($grandparentIds), '?'));
        $sql = "SELECT * FROM people WHERE id IN ($placeholders)";
        $stmt = Database::executeQuery($sql, $grandparentIds);
        $grandparents = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $grandparent = new Person();
            $grandparent->loadFromArray($row);
            $grandparents[] = $grandparent;
        }

        return $grandparents;
    }

    /**
     * @throws \Exception
     * Метод для отримання інформації про онуків
     */
    public function getGrandchildren(): array {
        $children = $this->getChildren();
        $childIds = array_map(function ($child) {
            return $child->id;
        }, $children);

        if (empty($childIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($childIds), '?'));
        $sql = "SELECT * FROM people WHERE father_id IN ($placeholders) OR mother_id IN ($placeholders)";
        $stmt = Database::executeQuery($sql, array_merge($childIds, $childIds));
        $grandchildren = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $grandchild = new Person();
            $grandchild->loadFromArray($row);
            $grandchildren[] = $grandchild;
        }

        return $grandchildren;
    }
}
