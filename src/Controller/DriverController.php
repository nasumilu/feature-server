<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/drivers', requirements: ['_format' => 'html|json'], format: 'json')]
class DriverController extends AbstractController
{
    /**
     * Gets a list of available datasource database drivers.
     * @param Request $request
     * @return Response
     */
    #[Route('.{_format}', name: 'datasource-drivers', methods: 'get')]
    public function list(Request $request): Response
    {
        $drivers = [
            ['label' => 'PostgreSQL', 'driver' => 'pdo_pgsql'],
            ['label' => 'MySQL', 'driver' => 'pdo_mysql'],
            ['label' => 'SQL Server', 'driver' => 'pdo_sqlsrv']
        ];
        return match($request->getRequestFormat('json')) {
            'html' => $this->render('driver/list.html.twig', ['drivers' => $drivers]),
            default => $this->json($drivers)
};
    }
}
