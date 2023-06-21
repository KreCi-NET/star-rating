<?php

namespace KreCiNET\StarRating;

class RatingManager
{
    private StorageInterface $storageManager;

    public function __construct(StorageInterface $storageManager)
    {
        $this->storageManager = $storageManager;
    }

    public function getRating($productID)
    {
        $product = new ProductClass($productID, $this->storageManager);
        return $product->getJSON();
    }

    public function setRating($productID, $rating)
    {
        $product = new ProductClass($productID, $this->storageManager);
        $product->setRating($rating);
    }
}