<?php

namespace KreCiNET\StarRating;

use PHPUnit\Framework\TestCase;

class RatingManagerTest extends TestCase {
  private $ratingManager;
  private $csvFile = 'test_ratings.csv';
  private $storageManager;

  protected function setUp(): void {
    $this->storageManager = new StorageCSV($this->csvFile);
    $this->ratingManager = new RatingManager($this->storageManager);
    $this->prepareTestData();
  }

  protected function tearDown(): void {
    if (file_exists($this->csvFile)) {
      unlink($this->csvFile);
    }
  }

  private function prepareTestData(): void {
    // Prepare test data
    $data = [
      ['1', '10', '4.5'],
      ['2', '1', '5'],
      ['3', '1', '5']
    ];

    // Write test data to CSV file
    $handle = fopen($this->csvFile, 'w');
    foreach ($data as $row) {
      fputcsv($handle, $row);
    }
    fclose($handle);
  }

  public function testGetRating(): void {
    $this->assertEquals('{"average":4.5,"total":10}', $this->ratingManager->getRating(1));
    $this->assertEquals('{"average":5,"total":1}', $this->ratingManager->getRating(2));
    $this->assertEquals('{"average":5,"total":1}', $this->ratingManager->getRating(3));
  }

  public function testSetRating(): void {
    $this->ratingManager->setRating(5, 4.5);
    $this->assertEquals('{"average":4.5,"total":1}', $this->ratingManager->getRating(5));

    $this->ratingManager->setRating(5, 3);
    $this->assertEquals('{"average":3.75,"total":2}', $this->ratingManager->getRating(5));
  }
}

?>
