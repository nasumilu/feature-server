<?php

namespace App\Repository;

use App\Model\Datasource;
use App\Service\Database;
use Doctrine\DBAL\Exception;

class DatasourceRepository
{

    public function __construct(private readonly Database $database)
    {
    }

    /**
     * @throws Exception
     */
    public function listDatasources(int $page = 0, int $limit = 10): array
    {
        return $this->database->connect()->createQueryBuilder()
            ->select('id', 'dbname', 'username', 'host', 'driver', 'port', 'name', 'comment')
            ->from('datasource')
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit)
            ->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function find(int $id): array|null
    {
        $results = $this->database->connect()->createQueryBuilder()
            ->select('id', 'dbname', 'username', 'host', 'driver', 'port', 'name', 'comment')
            ->from('datasource')
            ->where('id = :id')
            ->setParameters(['id' => $id])
            ->fetchAssociative();
        return $results ?: null;

    }

    /**
     * @throws Exception
     */
    public function save(Datasource $datasource): array
    {
        if (!$datasource->id) {
            $this->database->connect()->insert('datasource', $datasource->asArray());
        } else {
            $this->database->connect()->update('datasource', $datasource->asArray(), ['id' => $datasource->id]);
        }
        return $this->find($datasource->name);
    }

}