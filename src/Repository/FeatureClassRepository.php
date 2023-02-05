<?php

namespace App\Repository;

use App\Service\Database;
use Doctrine\DBAL\Exception;

class FeatureClassRepository
{

    public function __construct(private readonly Database $database)
    {
    }

    /**
     * @throws Exception
     */
    public function findFeatureClass(string $service, string $feature): array
    {
        return $this->database->connect()
            ->executeQuery('select 
                                    s.name service, 
                                    f.name as id,
                                    f.title,
                                    f.description,
                                    f.extent
                                from feature_class f JOIN service s ON f.service = s.id 
                                where s.name = :service and f.name = :feature',
                ['service' => $service, 'feature' => $feature])
            ->fetchAssociative();
    }

}