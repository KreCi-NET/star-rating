<?php

namespace net\kreci\StarRating;

require '../src/rating.php';

use PHPUnit\Framework\TestCase;

class RatingManagerTest extends TestCase {
  private $ratingManager;

  protected function setUp(): void {
    $this->ratingManager = new RatingManager('test_ratings.csv');
  }

  protected function tearDown(): void {
    if (file_exists('test_ratings.csv')) {
      unlink('test_ratings.csv');
    }
  }

  public function testGetRating(): void {
    $this->assertEquals('{"average":4.5,"total":10}', $this->ratingManager->getRating(1));
    $this->assertEquals('{"average":5,"total":1}', $this->ratingManager->getRating(2));
    $this->assertEquals('{"average":5,"total":1}', $this->ratingManager->getRating(3));
  }

  public function testSetRating(): void {
    $this->ratingManager->setRating(1, 4.5);
    $this->assertEquals('{"average":4.5,"total":1}', $this->ratingManager->getRating(1));

    $this->ratingManager->setRating(1, 3);
    $this->assertEquals('{"average":3.75,"total":2}', $this->ratingManager->getRating(1));
  }
}

?>
