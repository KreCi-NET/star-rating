<?php

namespace KreCiNET\StarRating;

interface StorageInterface
{
    public function readRating(int $productID): ?array;
    public function saveRating(int $productID, array $rating);
}