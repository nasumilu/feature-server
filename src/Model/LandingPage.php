<?php

namespace App\Model;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Annotation\Ignore;

use function array_diff_key;
use function array_intersect_key;
use function array_filter;

class LandingPage
{

    private function __construct(#[Ignore] private readonly UrlGeneratorInterface $urlGenerator,
                                #[Ignore] public readonly int                    $id,
                                #[Ignore] public readonly string                 $name,
                                public readonly string|null                      $title,
                                public readonly string|null                      $description,
                                public readonly array                            $links
    )
    {
    }

    /**
     * @throws Exception
     */
    public static function instance(Request $request, UrlGeneratorInterface $urlGenerator, ServiceRepository $repository, string $name): self|null
    {
        if (null === $service = $repository->findService($name)) {
            return null;
        }

        $format = $request->getRequestFormat('json');

        $routeArgs = ['name' => $service['name']];
        $htmlArgs = array_merge($routeArgs, ['_format' => 'html']);
        $service['links'] = [
            // self
            [
                'href' => $urlGenerator->generate('landing_page', $routeArgs, UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => $format === 'json' ? 'self' : 'alternative', 'type' => 'application/json', 'title' => ($data['title'] ?? '') . ' service description'
            ],
            [
                'href' => $urlGenerator->generate('landing_page', $htmlArgs, UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => $format === 'html' ? 'self' : 'alternative', 'type' => 'text/html', 'title' => ($data['title'] ?? '') . ' service description'
            ],

            // conformance
            [
                'href' => $urlGenerator->generate('conformance', $routeArgs, UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => 'conformance', 'type' => 'application/json', 'title' => 'Service OGC API conformance classes'
            ],
            [
                'href' => $urlGenerator->generate('conformance', $htmlArgs, UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => 'conformance', 'type' => 'text/html', 'title' => 'Service OGC API conformance classes'
            ],

            // collections
            [
                'href' => $urlGenerator->generate('collections', $routeArgs, UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => 'data', 'type' => 'application/json', 'title' => 'Information about ' . strtolower($data['title'] ?? 'Feature') . ' collections'
            ],
            [
                'href' => $urlGenerator->generate('collections', $htmlArgs, UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => 'data', 'type' => 'text/html', 'title' => 'Information about ' . strtolower($data['title'] ?? 'Feature') . ' collections'
            ]
        ];


        return new self($urlGenerator, ...$service);
    }
}