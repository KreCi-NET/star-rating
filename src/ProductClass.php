<?php

namespace KreCiNET\StarRating;

class ProductClass
{
    private int $productID;
    private float $ratingAverage;
    private int $ratingTotal;
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
        } else {
            $this->ratingAverage = 5;
            $this->ratingTotal = 1;
        }
    }

    private function saveProduct()
    {
        $ratingData['total'] = $this->ratingTotal;
        $ratingData['average'] = $this->ratingAverage;
        $this->storageManager->saveRating($this->productID, $ratingData);
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

    public function getJSON(): string
    {
        $ratingData['id'] = $this->productID;
        $ratingData['total'] = $this->ratingTotal;
        $ratingData['average'] = $this->ratingAverage;
        return json_encode($ratingData);
    }
}