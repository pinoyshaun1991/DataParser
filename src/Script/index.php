<?php

use future\Controller\ParseController;

require_once __DIR__ . '/../../vendor/autoload.php';

$parseData = new ParseController();

$directory = readline('Enter a directory: ');

if (empty($directory)) {
    throw new Exception('Please enter a directory');
}

try {
    $parseData->generateCSV($directory);
} catch (Exception $e) {
    print($e->getMessage());
}
