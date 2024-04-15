<?php

require 'vendor/autoload.php';

use Mariana\FamilyTree\{Database, Person, Tree};

// Підключення до бази даних (використовується для всіх запитів)
Database::getInstance();

// Визначення команди з аргументів командного рядка
$command = $argv[1] ?? null;

// Проста обробка команд
switch ($command) {
    case 'show-person':
        $personId = $argv[2] ?? null;
        if (!$personId) {
            echo "Person ID is required.\n";
            break;
        }
        try {
            $person = new Person($personId);
            $tree = new Tree($person);
            print_r($tree->getFamilyTreeAsArray());
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
        break;
    case 'show-descendants':
        $personId = $argv[2] ?? null;
        if (!$personId) {
            echo "Person ID is required.\n";
            break;
        }
        try {
            $person = new Person($personId);
            $tree = new Tree($person);
            print_r($tree->getDescendants($personId));
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
        break;
    case 'show-ancestors':
        $personId = $argv[2] ?? null;
        if (!$personId) {
            echo "Person ID is required.\n";
            break;
        }
        try {
            $person = new Person($personId);
            $tree = new Tree($person);
            print_r($tree->getAncestors($personId));
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
        break;

    default:
        echo "Unknown command.\n";
        break;
}
