<?php

namespace App\Controller;

use App\Model\Collection;
use App\Repository\FeatureClassRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{service}/collections')]
class CollectionController extends AbstractController
{
    public function __construct(
        private readonly FeatureClassRepository $featureClassRepository)
    {
    }

    #[Route('/{feature}.{_format}', name: 'collection', requirements: ['_format' => 'json|html'], format: 'json')]
    public function index(Request $request, string $service, string $feature): Response
    {
        if (null === $featureClass = $this->featureClassRepository->findFeatureClass($service, $feature)) {
            throw new NotFoundHttpException('Feature service $name not found');
        }
        $service = $featureClass['service'];
        unset($featureClass['service']);
        $collection = Collection::instance($request, $this->container->get('router'), $service, $featureClass);

        return $this->json($collection);
    }
}
