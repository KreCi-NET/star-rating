<?php

// This is how to use example file.

namespace KreCiNET\StarRating;

// Use composer to generate autoload file or include required classes
require_once('../vendor/autoload.php');

// You may filter your input if it goes from public website.
@$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
@$rating = filter_input(INPUT_GET, 'rating', FILTER_VALIDATE_FLOAT);
@$type = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(set|get)$/")));


// Create StorageManager object (you may create your own by implementing StorageInterface)
$storageManager = new StorageCSV('ratings.csv');
// Create RatingManager object and pass StorageManager to store ratings.
$ratingManager = new RatingManager($storageManager);

// Set and get rating data with RatingManager::setRating and RatingManager::getRating methods.
if ($rating !== false && $type === 'set') {
    $ratingManager->setRating($productId, $rating);
} elseif ($type === 'get') {
    $productRating = $ratingManager->getRating($productId);
    echo $productRating;
    //echo $_SESSION['csrfToken'];
}