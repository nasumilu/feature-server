<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Collection
{

    private function __construct(
        public readonly string $id,
        public readonly string | null $title,
        public readonly string | null $description,
        public readonly array | null $extent,
        public readonly string $itemType,
        public readonly array $links
    )
    {
    }

    public static function instance(Request $request, UrlGeneratorInterface $urlGenerator, string $service, array $data) : Collection {
        $box2d = [];
        preg_match_all('/[-+]?(\d*\.\d+|\d+){4}/', $data['extent'], $box2d, PREG_PATTERN_ORDER);
        $data['extent'] = ['spatial' => ['bbox' => array_map(floatval(...), $box2d[0])]];
        $data['itemType'] = 'feature';
        $routeArgs = ['service' => $service, 'feature' => $data['id']];
        $format = $request->getRequestFormat('json');
        $data['links'] = [
            array_filter([
                'href' => $urlGenerator->generate('collection', $routeArgs, UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => $format === 'json' ? 'self' : 'alternative', 'type'=> 'application/json', 'title' => $data['title'] ?? null
            ]),
            array_filter([
                'href' => $urlGenerator->generate('collection', array_merge($routeArgs, ['_format' => 'html']), UrlGeneratorInterface::ABSOLUTE_URL),
                'rel' => $format === 'html' ? 'self' : 'alternative', 'type' => 'text/html', 'title' => $data['title'] ?? null
            ])
        ];
        return new self(...$data);
    }

}