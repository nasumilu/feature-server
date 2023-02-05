<?php

namespace App\Service;

interface DecryptInterface
{

    public function decrypt(string $payload): string;

}