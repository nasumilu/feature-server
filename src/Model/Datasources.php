<?php

namespace App\Model;

use App\Repository\DatasourceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Datasources
{

    private function __construct(public readonly array $datasources, public readonly array $links = [])
    {
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public static function instance(Request $request, UrlGeneratorInterface $urlGenerator, DatasourceRepository $repository): self {
        // pagination
        $page = $request->query->get('page', 0);
        $limit = $request->query->get('limit', 10);
        $datasources = array_map(
            fn (array $datasource): Datasource => Datasource::instance($request, $urlGenerator, $datasource),
            $repository->listDatasources($page, $limit));
        $format = $request->getRequestFormat('json');
        $links = [
            [
                'href' => $urlGenerator->generate('datasource-list', referenceType: UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' =>  $format === 'json' ? 'self' : 'alternative', 'type' => 'application/json'
            ],
            [
                'href' => $urlGenerator->generate('datasource-list', ['_format' => 'html'], UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' =>  $format === 'html' ? 'self' : 'alternative', 'type' => 'text/html'
            ]
        ];

        return new self($datasources, $links);
    }

}