<?php

namespace App\Repository;

use App\Service\Database;
use Doctrine\DBAL\Exception;

class ServiceRepository
{

    public function __construct(private readonly Database $database)
    {
    }

    /**
     * @throws Exception
     */
    public function findService(string $name): array|null
    {
        $results = $this->database->connect()
            ->executeQuery('SELECT * FROM service WHERE name = :name', ['name' => $name])
            ->fetchAssociative();
        return $results ?: null;
    }

    public function listServiceNames(): array
    {
        return $this->database->connect()
            ->executeQuery('SELECT name FROM service')
            ->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function listFeatureClasses(string $name): array | null
    {
        $results = $this->database->connect()
            ->executeQuery(
                'select f.name id, f.title, f.description, f.extent 
                     from feature_class f 
                         join service s on s.id = f.service
                     where s.name = :name',
                ['name' => $name])
            ->fetchAllAssociative();
        return $results ?: null;
    }

}