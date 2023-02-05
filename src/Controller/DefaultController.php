<?php

namespace App\Controller;

use App\Model\Collections;
use App\Model\LandingPage;
use App\Model\Services;
use App\Repository\ServiceRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    public function __construct(private readonly ServiceRepository $repository)
    {
    }

    #[Route(['/', '/index.{_format}'], name: 'index', requirements: ['_format' => 'json|html'], format: 'json')]
    public function index(Request $request): Response
    {
        $services = Services::instance($request, $this->container->get('router'), $this->repository);
        return match($request->getRequestFormat('json')) {
            'html' => $this->render('default/services.html.twig', ['services' => $services]),
            default => $this->json($services)
        };
    }

    #[Route('/{name}.{_format}', name: 'landing_page', requirements: ['_format' => 'json|html'], format: 'json')]
    public function service(Request $request, string $name): Response
    {
        if (null === $landingPage = LandingPage::instance($request, $this->container->get('router'), $this->repository, $name)) {
            throw new NotFoundHttpException('Feature service $name not found');
        }
        return match ($request->getRequestFormat()) {
            'html' => $this->render('default/landing-page.html.twig', ['landing_page' => $landingPage]),
            default => $this->json($landingPage)
        };
    }

    /**
     * @throws Exception
     */
    #[Route('/{name}/conformance.{_format}', name: 'conformance', requirements: ['_format' => 'json|html'], format: 'json')]
    public function conformance(Request $request, string $name): Response
    {
        if (null === $service = $this->repository->findService($name)) {
            throw new NotFoundHttpException('Feature service $name not found');
        }
        $conformance = [
            'http://www.opengis.net/spec/ogcapi-features-1/1.0/conf/core',
            'http://www.opengis.net/spec/ogcapi-features-1/1.0/conf/oas30',
            'http://www.opengis.net/spec/ogcapi-features-1/1.0/conf/html',
            'http://www.opengis.net/spec/ogcapi-features-1/1.0/conf/geojson'
        ];
        return match ($request->getRequestFormat('json')) {
            'html' => $this->render('default/conformance.html.twig', ['service' => $service, 'conforms_to' => $conformance]),
            default => $this->json(['conformsTo' => $conformance])
        };
    }

    /**
     * @throws Exception
     */
    #[Route('/{name}/collections.{_format}', name: 'collections', requirements: ['_format' => 'json|html'], format: 'json')]
    public function collections(Request $request, string $name): Response
    {
        if (null == $collections = Collections::instance($request, $this->container->get('router'), $this->repository, $name)) {
            throw new NotFoundHttpException('Feature service $name not found');
        }
        return match($request->getRequestFormat('json')) {
            'html' => $this->render('default/collections.html.twig', ['collections' => $collections]),
            default => $this->json($collections)
        };
    }
}
