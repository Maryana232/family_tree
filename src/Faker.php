<?php

namespace Marianna\FamilyTree;

use Faker\Factory as FakerFactory;
use Marianna\FamilyTree\Database;

/**
 * Class Faker
 * @package Marianna\FamilyTree
 * Генерує випадкових людей та зберігає їх в базу даних
 */
class Faker
{
    private int $numberOfPeople;

    /**
     * Конструктор приймає кількість людей, яких потрібно згенерувати
     */
    public function __construct(int $numberOfPeople)
    {
        $this->numberOfPeople = $numberOfPeople;
    }

    /**
     * Метод для збереження людей в базу даних
     */
    public function savePeopleToDatabase(): void
    {
        $faker = FakerFactory::create();
        $connection = Database::getConnection();

        for ($i = 0; $i < $this->numberOfPeople; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $gender = $faker->randomElement(['ч', 'ж']);
            $birthDate = $faker->date;

            // Генерація дати смерті
            do {
                $deathDate = $faker->boolean(50) ? $faker->date : null;
            } while ($deathDate && strtotime($deathDate) < strtotime($birthDate . ' +10 years'));

            $sql = "INSERT INTO people (first_name, last_name, gender, birth_date, death_date) VALUES (?, ?, ?, ?, ?)";
            Database::executeQuery($sql, [$firstName, $lastName, $gender, $birthDate, $deathDate]);
        }
    }
}
