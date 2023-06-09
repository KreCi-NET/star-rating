<?php

namespace KreCiNET\StarRating;

class RatingManager {
    private $csvFile;

    public function __construct($csvFile) {
        $this->csvFile = $csvFile;
    }

    public function getRating($productId) {
        $ratingData = $this->getRatingDataFromCSV($productId);
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

    public function setRating($productId, $rating) {
        $ratingData = $this->getRatingDataFromCSV($productId);

        if ($ratingData === null) {
            $ratingData = [
                'total' => 0,
                'average' => 0
            ];
        }

        $ratingData['total']++;
        $ratingData['average'] = (($ratingData['average'] * ($ratingData['total'] - 1)) + $rating) / $ratingData['total'];

        $this->updateRatingDataInCSV($productId, $ratingData);
    }

    private function getRatingDataFromCSV($productId) {
        if (!file_exists($this->csvFile)) {
            return null;
        }

        $handle = fopen($this->csvFile, 'r');
        while (($data = fgetcsv($handle)) !== false) {
            if ($data[0] == $productId) {
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

    private function updateRatingDataInCSV($productId, $ratingData) {
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