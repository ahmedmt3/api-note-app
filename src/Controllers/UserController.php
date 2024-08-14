<?php

namespace App\Controllers;

class UserController
{


    public function processRequest(string $method, string $id)
    {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, string $id): void {}

    private function processCollectionRequest(string $method): void {}
}
