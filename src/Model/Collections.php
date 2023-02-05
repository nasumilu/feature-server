<?php

namespace App\Model;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Annotation\Ignore;

class Collections
{
    private function __construct(
        #[Ignore] public readonly array $service,
        public readonly array $links,
        public readonly array $collections)
    {
    }

    /**
     * @throws Exception
     */
    public static function instance(Request $request, UrlGeneratorInterface $urlGenerator, ServiceRepository $repository, string $name): Collections | null
    {
        if (null === $service = $repository->findService($name)) {
            return null;
        }
        $collections = array_map(
            fn(array $row): Collection => Collection::instance($request, $urlGenerator, $service['name'], $row),
            $repository->listFeatureClasses($name) ?? []
        );
        $routeArgs = ['name' => $service['name']];
        $format = $request->getRequestFormat('json');
        $links = [
            // self
            [
                'href' => $urlGenerator->generate('collections', $routeArgs, UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => $format === 'json' ? 'self' : 'alternative', 'type' => 'application/json',
                'title' => 'Information about ' . strtolower($data['title'] ?? 'Feature') . ' collections'
            ],
            [
                'href' => $urlGenerator->generate('collections', array_merge(['_format' => 'html'], $routeArgs), UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => $format === 'html' ? 'self' : 'alternative', 'type' => 'text/html',
                'title' => 'Information about ' . strtolower($data['title'] ?? 'Feature') . ' collections'
            ]
            // add map package download link here
        ];
        return new self($service, $links, $collections);
    }
}