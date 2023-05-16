<?php

namespace net\kreci\StarRating;

require_once('RatingManager.php');

// How To Use:
@$productId = $_GET['id'];
@$rating = $_GET['rating'];
@$type = $_GET['type'];

$ratingManager = new RatingManager('ratings.csv');

if (isset($rating) && $type == 'set') {
    $ratingManager->setRating($productId, $rating);
} elseif ($type == 'get') {
    $productRating = $ratingManager->getRating($productId);
    echo $productRating;
}