<?php

namespace Mariana\FamilyTree;

use Exception;
use PDO;

/**
 * Class Tree
 * @package Mariana\FamilyTree
 * Клас для побудови фамільного дерева
 */
class Tree
{
    private Person $person;

    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * Повертає інформацію про онуків
     * @throws Exception
     */
    public function getGrandchildren(): array
    {
        return $this->person->getGrandchildren();
    }

    /**
     * Повертає інформацію про дітей
     * @throws Exception
     */
    public function getChildren(): array
    {
        return $this->person->getChildren();
    }

    /**
     * Повертає інформацію про братів і сестер
     * @throws Exception
     */
    public function getSiblings(): array
    {
        return $this->person->getSiblings();
    }

    /**
     * Повертає інформацію про батьків
     * @throws Exception
     */
    public function getParents(): array
    {
        $parents = [];
        if ($father = $this->person->getFather()) {
            $parents[] = $father;
        }
        if ($mother = $this->person->getMother()) {
            $parents[] = $mother;
        }
        return $parents;
    }

    /**
     * Повертає інформацію про дідусів та бабусь
     * @throws Exception
     */
    public function getGrandparents(): array
    {
        return $this->person->getGrandparents();
    }

    /**
     * Вивід структури фамільного дерева
     * @throws Exception
     */
    public function printFamilyTree(): void
    {
        echo "Person: " . $this->person->getFirstName() . " " . $this->person->getLastName() . "\n";
        echo "Parents:\n";
        foreach ($this->getParents() as $parent) {
            echo "  - " . $parent->getFirstName() . " " . $parent->getLastName() . "\n";
        }
        echo "Siblings:\n";
        foreach ($this->getSiblings() as $sibling) {
            echo "  - " . $sibling->getFirstName() . " " . $sibling->getLastName() . "\n";
        }
        echo "Children:\n";
        foreach ($this->getChildren() as $child) {
            echo "  - " . $child->getFirstName() . " " . $child->getLastName() . "\n";
        }
        echo "Grandchildren:\n";
        foreach ($this->getGrandchildren() as $grandchild) {
            echo "  - " . $grandchild->getFirstName() . " " . $grandchild->getLastName() . "\n";
        }
        echo "Grandparents:\n";
        foreach ($this->getGrandparents() as $grandparent) {
            echo "  - " . $grandparent->getFirstName() . " " . $grandparent->getLastName() . "\n";
        }
    }

    /**
     * @throws Exception
     */
    public function getFamilyTreeAsArray(): array
    {
        return [
            'person' => $this->getPersonDetails($this->person),
            'parents' => $this->getParentsDetails(),
            'siblings' => $this->getSiblingsDetails(),
            'children' => $this->getChildrenDetails(),
            'grandchildren' => $this->getGrandchildrenDetails(),
            'grandparents' => $this->getGrandparentsDetails()
        ];
    }

    private function getPersonDetails(Person $person): array
    {
        return [
            'id' => $person->getId(),
            'first_name' => $person->getFirstName(),
            'last_name' => $person->getLastName(),
            'gender' => $person->getGender(),
            'birth_date' => $person->getBirthDate(),
            'death_date' => $person->getDeathDate()
        ];
    }

    /**
     * @throws Exception
     */
    private function getParentsDetails(): array
    {
        return array_map([$this, 'getPersonDetails'], $this->getParents());
    }

    /**
     * @throws Exception
     */
    private function getSiblingsDetails(): array
    {
        return array_map([$this, 'getPersonDetails'], $this->getSiblings());
    }

    /**
     * @throws Exception
     */
    private function getChildrenDetails(): array
    {
        return array_map([$this, 'getPersonDetails'], $this->getChildren());
    }

    /**
     * @throws Exception
     */
    private function getGrandchildrenDetails(): array
    {
        return array_map([$this, 'getPersonDetails'], $this->getGrandchildren());
    }

    /**
     * @throws Exception
     */
    private function getGrandparentsDetails(): array
    {
        return array_map([$this, 'getPersonDetails'], $this->getGrandparents());
    }

    /**
     * Отримує всіх нащадків вказаної особи рекурсивно.
     *
     * @param int $personId Ідентифікатор особи, нащадків якої потрібно знайти.
     * @return array Масив об'єктів класу Person, які представляють нащадків.
     * @throws Exception
     */
    public static function getDescendants(int $personId): array
    {
        return self::fetchDescendants($personId);
    }

    /**
     * Допоміжна функція для рекурсивного отримання нащадків.
     *
     * @param int $personId Ідентифікатор особи, нащадків якої потрібно знайти.
     * @param array $descendants Масив для збору нащадків.
     * @return array Масив об'єктів класу Person, які представляють нащадків.
     * @throws Exception
     */
    private static function fetchDescendants(int $personId, array &$descendants = []): array
    {
        $sql = "SELECT * FROM people WHERE father_id = ? OR mother_id = ?";
        $stmt = Database::executeQuery($sql, [$personId, $personId]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $descendant = new Person();
            $descendant->loadFromArray($row);
            $descendants[] = $descendant;
            self::fetchDescendants($descendant->getId(), $descendants);
        }

        return $descendants;
    }

    /**
     * Отримує всіх предків вказаної особи рекурсивно.
     *
     * @param int $personId Ідентифікатор особи, предків якої потрібно знайти.
     * @return array Масив об'єктів класу Person, які представляють предків.
     * @throws Exception
     */
    public static function getAncestors(int $personId): array
    {
        return self::fetchAncestors($personId);
    }

    /**
     * Допоміжна функція для рекурсивного отримання предків.
     *
     * @param int $personId Ідентифікатор особи, предків якої потрібно знайти.
     * @param array $ancestors Масив для збору предків.
     * @return array Масив об'єктів класу Person, які представляють предків.
     * @throws Exception
     */
    private static function fetchAncestors(int $personId, array &$ancestors = []): array
    {
        // Запит на отримання батька та матері окремо
        $sql = "SELECT * FROM people WHERE id = ?";
        $stmt = Database::executeQuery($sql, [$personId]);

        $person = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($person) {
            $fatherId = $person['father_id'];
            $motherId = $person['mother_id'];

            // Якщо є батько, рекурсивно викликаємо для батька
            if ($fatherId) {
                $fatherStmt = Database::executeQuery($sql, [$fatherId]);
                $father = $fatherStmt->fetch(PDO::FETCH_ASSOC);
                if ($father) {
                    $ancestor = new Person();
                    $ancestor->loadFromArray($father);
                    $ancestors[] = $ancestor;
                    self::fetchAncestors($fatherId, $ancestors);
                }
            }

            // Якщо є мати, рекурсивно викликаємо для матері
            if ($motherId) {
                $motherStmt = Database::executeQuery($sql, [$motherId]);
                $mother = $motherStmt->fetch(PDO::FETCH_ASSOC);
                if ($mother) {
                    $ancestor = new Person();
                    $ancestor->loadFromArray($mother);
                    $ancestors[] = $ancestor;
                    self::fetchAncestors($motherId, $ancestors);
                }
            }
        }

        return $ancestors;
    }
}
