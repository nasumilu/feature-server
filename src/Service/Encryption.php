<?php

namespace App\Service;

class Encryption implements EncryptInterface, DecryptInterface
{

    public function __construct(private readonly string $cipher, private readonly string $key)
    {
    }

    public function decrypt(string $payload): string
    {
        [$data, $iv, $tag] = array_map('base64_decode', explode('.', $payload));
        return openssl_decrypt($data, $this->cipher, base64_decode($this->key), iv: $iv, tag: $tag);
    }

    public function encrypt(string $payload): string
    {
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $value = openssl_encrypt($payload, $this->cipher, base64_decode($this->key), iv: $iv, tag: $tag);
        return base64_encode($value) . '.' . base64_encode($iv) . '.' . base64_encode($tag);
    }
}