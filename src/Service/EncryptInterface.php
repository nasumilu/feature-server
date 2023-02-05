<?php

namespace App\Service;

interface EncryptInterface
{

    public function encrypt(string $payload): string;

}