<?php

namespace App\Model;

use App\Repository\DatasourceRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Datasource
{

    private Connection | null $connection = null;

    public function __construct(
        public readonly string $name,
        public readonly string $dbname,
        public readonly string $driver,
        public readonly string $host,
        public readonly int $port,
        public readonly string $username,
        public int | null $id = null,
        public string | null $password = null,
        public string | null $comment = null,
        public array $links = []
    )
    {
    }

    /**
     * @throws Exception
     */
    public function connect(): Connection {
        if (null === $this->connection) {
            $this->connection = DriverManager::getConnection([
                'dbname' => $this->dbname,
                'user' => $this->username,
                'password' => $this->password,
                'host' => $this->host,
                'driver' => $this->driver
            ]);
            $this->connection->connect();
        }
        return $this->connection;
    }

    public function asArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'comment' => $this->comment,
            'dbname' => $this->dbname,
            'port' => $this->port,
            'username' => $this->username,
            'password' => $this->password,
            'host' => $this->host,
            'driver' => $this->driver
        ]);
    }

    public function __toString(): string {
        $data = $this->asArray();
        unset($data['password']);
        $str = '';
        foreach($data as $key=>$value) {
            $str .= "$key:$value;";
        }
        return $str;
    }


    public static function instance(Request $request, UrlGeneratorInterface $urlGenerator, array $datasource): self|null {
        $routeArgs = ['id' => $datasource['id']];
        $format = $request->getRequestFormat('json');
        $links = [
            [
                'href' => $urlGenerator->generate('datasource-item', $routeArgs, UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' =>  $format === 'json' ? 'self' : 'alternative', 'type' => 'application/json'
            ],
            [
                'href' => $urlGenerator->generate('datasource-item', array_merge($routeArgs, ['_format' => 'html']), UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' =>  $format === 'html' ? 'self' : 'alternative', 'type' => 'text/html'
            ]
        ];
        $datasource['links']  = $links;
        return new self(...$datasource);
    }

}