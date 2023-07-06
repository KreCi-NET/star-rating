<?php

namespace KreCiNET\StarRating;

class RatingManager
{
    private StorageInterface $storageManager;
    private $csrfToken;

    public function __construct(StorageInterface $storageManager)
    {
        $this->storageManager = $storageManager;
        $this->generateCSRFToken();
    }

    private function generateCSRFToken() {
        session_start();
        if (!isset($_SESSION['csrfToken']) || time() - $_SESSION['csrfTokenCreatedAt'] > 3600) {
            $this->csrfToken = bin2hex(random_bytes(32));
            $_SESSION['csrfToken'] = $this->csrfToken;
            $_SESSION['csrfTokenCreatedAt'] = time();
        }
        $this->csrfToken ??= $_SESSION['csrfToken'] ?? null;
        
    }

    private function validateCSRFToken() {
        if (isset($this->csrfToken)
         && isset($_SESSION['csrfToken'])
         && isset($_SERVER['HTTP_X_CSRF_TOKEN'])
         && time() - $_SESSION['csrfTokenCreatedAt'] < 3600
         && $_SESSION['csrfToken'] == $_SERVER['HTTP_X_CSRF_TOKEN']) {
            return true;
        }
        return false;
    }

    public function getRating($productID)
    {
        $product = new ProductClass($productID, $this->storageManager);
        $ratingData['id'] = $productID;
        $ratingData['average'] = $product->getRating();
        $ratingData['total'] = $product->getTotal();
        $ratingData['csrfToken'] = $this->csrfToken;
        return json_encode($ratingData);
    }

    public function setRating($productID, $rating)
    {
        if ($this->validateCSRFToken()) {
            $product = new ProductClass($productID, $this->storageManager);
            $product->setRating($rating);
        } else {
            http_response_code(403);
            exit();
        }
    }
}