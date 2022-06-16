<?php

namespace App\Controller;

use App\Entity\Post;
use App\Services\PostService;
use App\Services\ResponseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $postService = new PostService($this->entityManager);
        $postList = $postService->index($request);

        return ResponseService::sendList(
            $postList,
            $this->entityManager->getRepository(Post::class)->count([])
        );
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function create(
        Request                $request,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        try {
            $postService = new PostService($this->entityManager);
            $postService->create($request, $entityManager, $this->getUser());

            return ResponseService::sendItem([], 'post_created', Response::HTTP_CREATED);
        } catch (\Exception $exception) {

            return ResponseService::sendItem(
                [],
                $exception->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function show($id, Request $request): JsonResponse
    {
        $postService = new PostService($this->entityManager);
        $post = $postService->show($id);

        return ResponseService::sendItem($post);
    }

    /**
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(
        $id,
        EntityManagerInterface $entityManager
    ):JsonResponse
    {
        $postService = new PostService($this->entityManager);
        $postService->delete($id , $entityManager);

        return ResponseService::sendItem([], 'post_deleted', Response::HTTP_OK);

    }

}
