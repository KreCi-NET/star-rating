<?php

namespace KreCiNET\StarRating;

class StorageCSV implements StorageInterface
{
    private $csvFile;

    public function __construct($csvFile)
    {
        $this->csvFile = $csvFile;
    }

    public function readRating(int $productID): ?array
    {
        if (!file_exists($this->csvFile)) {
            return null;
        }

        $handle = fopen($this->csvFile, 'r');
        while (($data = fgetcsv($handle)) !== false) {
            if ($data[0] == $productID) {
                fclose($handle);
                return [
                    'total' => intval($data[1]),
                    'average' => floatval($data[2])
                ];
            }
        }
        fclose($handle);

        return null;
    }

    public function saveRating(int $productId, array $ratingData)
    {
        $data = [
            $productId,
            $ratingData['total'],
            $ratingData['average']
        ];

        if (!file_exists($this->csvFile)) {
            $handle = fopen($this->csvFile, 'w');
            fputcsv($handle, $data);
            fclose($handle);
            return;
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'csv');
        $handle = fopen($this->csvFile, 'r');
        $tempHandle = fopen($tempFile, 'w');
        $found = false;

        while (($row = fgetcsv($handle)) !== false) {
            if ($row[0] == $productId) {
                fputcsv($tempHandle, $data);
                $found = true;
            } else {
                fputcsv($tempHandle, $row);
            }
        }

        if (!$found) {
            fputcsv($tempHandle, $data);
        }

        fclose($handle);
        fclose($tempHandle);

        unlink($this->csvFile);
        rename($tempFile, $this->csvFile);
    }
}