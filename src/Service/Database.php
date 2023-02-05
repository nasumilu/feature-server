<?php

namespace App\Service;

use App\Repository\FeatureClassRepository;
use App\Repository\RepositoryInterface;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Nasumilu\DBAL\Driver\PostGISMiddleware;
use Nasumilu\DBAL\Schema\FeatureClass;

class Database
{
    private Connection | null $connection = null;

    public function __construct(
        private readonly string $url,
        private readonly EncryptInterface $encrypt,
        private readonly DecryptInterface $decrypt) { }

    public function connect() : Connection {
        if (null === $this->connection) {
            $configuration = new Configuration();
            $configuration->setMiddlewares([new PostGISMiddleware()]);
            $this->connection = DriverManager::getConnection(['url' => $this->url], $configuration);
        }
        return $this->connection;
    }

    /**
     * @throws Exception
     * @deprecated
     */
    public function findService(string $service): array | null {
        $sql = 'SELECT * FROM service WHERE name = :name';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue('name', $service);
        $result = $stmt->executeQuery();
        $value = $result->fetchAssociative();
        return $value ?: null;
    }

    /**
     * @deprecated
     * @return array
     * @throws Exception
     */
    public function listServices(): array {
        return $this->connect()->executeQuery('SELECT * FROM service')->fetchAllAssociative();
    }

    public function findFeatureClassesByService(int | array $service): array {
        if(is_array($service)) {
            $service = $service['id'];
        }
        $sql = 'SELECT * FROM feature_class WHERE service = :service';
        return $this->connect()->executeQuery($sql, ['service' => $service])->fetchAllAssociative();
    }

    public function listDatasources(int $page = 1, int $limit = 10): array {
        $connection = $this->connect();
        $sql = 'SELECT id, dbname, username, host, port, driver FROM datasource LIMIT :limit OFFSET :offset';
        $stmt = $connection->prepare($sql);
        $result = $stmt->executeQuery(['limit' => $limit, 'offset' => ($page - 1) * $limit])
            ->fetchAllAssociative();
        $count = $connection->executeQuery('SELECT * FROM datasource')->fetchOne();
        $next = ($page * $limit) < $count;

        return [
            'results' => $result,
            'total' => $count,
            'page' => $page,
            'limit' => $limit,
            'next' => $next
        ];
    }

    /**
     * @throws Exception
     */
    public function insertDatasource(array $options): int {
        $sql = 'INSERT INTO datasource (dbname, username, password, host, driver, port) VALUES (:dbname, :user, :password, :host, :driver, :port)';
        $stmt = $this->connect()->prepare($sql);
        $options['password'] = $this->encrypt->encrypt($options['password']);
        $result = $stmt->executeQuery($options);
        return $result->rowCount();
    }

    public function findServiceFeatureClass(string $service, string $featureClass): array {

    }

}