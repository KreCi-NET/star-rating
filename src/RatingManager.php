<?php

namespace KreCiNET\StarRating;

class RatingManager
{
    private StorageInterface $storageManager;

    public function __construct(StorageInterface $storageManager)
    {
        $this->storageManager = $storageManager;
    }

    public function getRating($productId)
    {
        $ratingData = $this->storageManager->readRating($productId);
        if ($ratingData !== null) {
            $response = array(
                'average' => round($ratingData['average'], 2),
                'total' => $ratingData['total']
            );
            return json_encode($response);
        } else {
            $response = array(
                'average' => 5,
                'total' => 1
            );
            return json_encode($response);
        }
    }

    public function setRating($productId, $rating)
    {
        $ratingData = $this->storageManager->readRating($productId);

        if ($ratingData === null) {
            $ratingData = [
                'total' => 0,
                'average' => 0
            ];
        }

        $ratingData['total']++;
        $ratingData['average'] = (($ratingData['average'] * ($ratingData['total'] - 1)) + $rating) / $ratingData['total'];

        $this->storageManager->saveRating($productId, $ratingData);
    }
}