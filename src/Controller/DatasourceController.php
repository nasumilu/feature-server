<?php

namespace App\Controller;

use App\Model\Datasource;
use App\Model\Datasources;
use App\Repository\DatasourceRepository;
use App\Service\EncryptInterface;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/datasources', requirements: ['_format' => 'json|html'], format: 'json')]
class DatasourceController extends AbstractController
{

    public function __construct(private readonly DatasourceRepository $repository)
    {
    }

    /**
     * @throws Exception
     */
    #[Route('.{_format}', name: 'datasource-list', methods: 'get')]
    public function list(Request $request): Response
    {
        $datasources = Datasources::instance($request, $this->container->get('router'), $this->repository);
        return match ($request->getRequestFormat('json')) {
            'html' => $this->render('datasource/index.html.twig', ['datasources' => $datasources]),
            default => $this->json($datasources)
        };
    }

    #[Route('/{id<\d+>}.{_format}', name: 'datasource-item', methods: 'get')]
    public function item(Request $request, int $id): Response
    {
        if(null === $datasource = $this->repository->find($id)) {
            throw new NotFoundHttpException("Datasource for id $id not found!");
        }
        return match($request->getRequestFormat('json')) {
            'html' => $this->render('datasource/item.html.twig', ['datasource' => $datasource]),
            default => $this->json(Datasource::instance($request, $this->container->get('router'), $datasource))
        };
    }

    /**
     * Test a datasource connection
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/test-connection.{_format}', name: 'datasource-test-connection', methods: ['options', 'post'])]
    public function test(Request $request, SerializerInterface $serializer): Response
    {
        $data = ['success' => false, 'message' => ''];
        try {
            $datasource = $serializer->deserialize($request->getContent(), Datasource::class, 'json');
            $datasource->connect();
            $data['success'] = true;
            $data['message'] = "Succeeded: $datasource";
        } catch (Exception $ex) {
            $data['success'] = false;
            $data['message'] = $ex->getMessage();
        }
        return match($request->getRequestFormat('json')) {
            'html' => $this->render('datasource/test-connection.html.twig', $data),
            default => $this->json($data)
        };
    }

    /**
     * Saves or updates a datasource
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EncryptInterface $encryption
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('/save.{_format}', name: 'datasource-save', methods: ['options', 'post'])]
    public function save(Request $request, SerializerInterface $serializer, EncryptInterface $encryption): Response
    {
        $datasource = $serializer->deserialize($request->getContent(), Datasource::class, 'json');
        $post = $serializer->deserialize($request->getContent(), Datasource::class, 'json');
        $post->password = $encryption->encrypt($post->password);
        $post->id = $datasource->id;
        $datasource = $this->repository->save($post);
        return match($request->getRequestFormat('json')) {
            'html' => $this->render('datasource/save.html.twig', ['datasource' => $datasource]),
            default => $this->json($datasource)
        };
    }

    /**
     * @throws Exception
     */
    #[Route('/update/{name}.{_format}', name: 'datasource-update', requirements: ['_format' => 'json|html'], methods: ['options', 'put'], format: 'json')]
    public function update(string $name, Request $request, SerializerInterface $serializer): Response
    {
        if (null === $datasource = $this->repository->find($name)) {
            throw new NotFoundHttpException("Datasource $name is not found!");
        }

        $put = $serializer->deserialize($request->getContent(), Datasource::class, 'json');
        $put->id = $datasource->id;
        $datasource = $this->repository->save($put);

        return match($request->getRequestFormat('json')) {
            'html' => $this->render('datasource/save.html.twig', ['datasource' => $datasource]),
            default => $this->json(['datasource' => $datasource])
        };
    }

}
