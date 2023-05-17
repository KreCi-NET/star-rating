<?php

namespace net\kreci\StarRating;

require_once('RatingManager.php');

// How To Use:
@$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
@$rating = filter_input(INPUT_GET, 'rating', FILTER_VALIDATE_FLOAT);
@$type = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(set|get)$/")));

$ratingManager = new RatingManager('ratings.csv');

if ($rating !== false && $type === 'set') {
    $ratingManager->setRating($productId, $rating);
} elseif ($type === 'get') {
    $productRating = $ratingManager->getRating($productId);
    echo $productRating;
}