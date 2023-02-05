<?php

namespace App\Model;

use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Services
{
    private function __construct(public readonly array $links, public readonly array $services) {}

    public static function instance(Request $request, UrlGeneratorInterface $urlGenerator, ServiceRepository $serviceRepository): Services {
        $services = $serviceRepository->listServiceNames();
        $format = $request->getRequestFormat('json');
        $isIndex = str_contains($request->getPathInfo(), 'index');
        $links = [
            [
                'href' => $request->getUriForPath('/'),
                'rel' => $format === 'json' && !$isIndex ? 'self' : 'alternative',
                'type' => 'application/json', 'title' => 'Feature Service(s)'
            ],
            [
                'href' => $request->getUriForPath('/index.json'),
                'rel' => $format === 'json' && $isIndex ? 'self' : 'alternative',
                'type' => 'application/json', 'title' => 'Feature Services(s)'
            ],
            [
                'href' => $request->getUriForPath('/index.html'),
                'rel' => $format === 'html' && $isIndex ? 'self' : 'alternative',
                'type' => 'text/html', 'title' => 'Feature Service(s)'
            ]
        ];

        $landingPages = array_map(fn(array $row) => LandingPage::instance($request, $urlGenerator, $serviceRepository, $row['name']), $services);
        return new self($links, $landingPages);
    }
}