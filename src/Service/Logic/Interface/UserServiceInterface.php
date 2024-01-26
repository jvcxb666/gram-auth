<?php 

namespace App\Service\Interface;

interface UserServiceInterface
{

    public function save(?array $data): ?array;
    public function delete(?int $data): ?array;

}