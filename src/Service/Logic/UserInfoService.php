<?php

namespace App\Service\Logic;

use App\Service\Logic\Decorator\ServiceDecorator;

class UserInfoService extends ServiceDecorator
{

    public function findUser(array $data): array
    {
        $data = $this->service->findUser($data);

        return $data;
    }

    public function save(?array $data): ?array
    {
        return $this->service->save($data);
    }

    public function delete(?int $id): ?array
    {
        return $this->service->delete($id);
    }
}