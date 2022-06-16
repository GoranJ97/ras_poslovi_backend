<?php

namespace App\Services;

use App\Entity\Post;
use App\Entity\User;
use App\Services\Validations\PostValidationService;
use App\Traits\PostTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostService
{
    use PostTrait;

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
     * @return array
     */
    public function index(Request $request): array
    {

        $postsList = $this->entityManager
            ->getRepository(Post::class)->findAll();
        $posts = [];

        foreach ($postsList as $post) {
            array_push($posts, $this->getPostData($post));
        }
        return $posts;

    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param User $user
     * @return Post
     * @throws \Exception
     */
    public function create(
        Request                $request,
        EntityManagerInterface $entityManager,
        User                   $user
    ): Post
    {
        $postValidationService = new PostValidationService($this->entityManager);
        $errors = $postValidationService->validatePost($request);

        if (count($errors) > 0) {
            throw new \Exception(json_encode($errors));
        }

        $post = new Post();
        $post->setUser($user);
        $post->setTitle($request->request->get('title'));
        $post->setContent($request->request->get('content'));

        $entityManager->persist($post);
        $entityManager->flush();

        return $post;

    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function show($id): array
    {
        $post = $this->entityManager
            ->getRepository(Post::class)->find(['id' => $id]);

        if (!$post) {
            throw new \Exception("Post with this id does not exist");
        }
        return $this->getPostData($post);
    }

    /**
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws \Exception
     */
    public function delete($id, EntityManagerInterface $entityManager)
    {
        $post = $this->entityManager
            ->getRepository(Post::class)->find(['id' => $id]);

        if (!$post) {
            throw new \Exception("Post does not exist");
        }
        $entityManager->remove($post);
        $entityManager->flush();

    }

}
