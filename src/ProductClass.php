<?php

namespace KreCiNET\StarRating;

class ProductClass
{
    private int $productID;
    private float $ratingAverage = 0;
    private int $ratingTotal = 0;
    private StorageInterface $storageManager;

    public function __construct(int $productID, StorageInterface $storageManager)
    {
        $this->productID = $productID;
        $this->storageManager = $storageManager;
        $this->loadProduct();
    }

    private function loadProduct()
    {
        $productData = $this->storageManager->readRating($this->productID);
        
        if ($productData !== null) {
            $this->ratingAverage = round($productData['average'], 2);
            $this->ratingTotal = $productData['total'];
        }
    }

    private function saveProduct()
    {
        $this->storageManager->saveRating($this->productID, $this->ratingTotal, $this->ratingAverage);
    }

    public function getRating(): float
    {
        return $this->ratingAverage;
    }

    public function getTotal(): int
    {
        return $this->ratingTotal;
    }

    public function setRating($rating)
    {
        $this->ratingTotal++;
        $this->ratingAverage = (($this->ratingAverage * ($this->ratingTotal - 1)) + $rating) / $this->ratingTotal;
        $this->saveProduct();
    }
}