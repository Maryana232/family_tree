<?php

require '../vendor/autoload.php';

use Marianna\FamilyTree\Application;

$app = Application::getInstance();
$app->run();


//$faker = new Marianna\FamilyTree\Faker(100);
//$faker->savePeopleToDatabase();

// консольна команда
// php console.php show-person 1
// php console.php show-person 7
// Де 1 та 7 - це id персони в базі даних, для прикладу взято 1 та 7
