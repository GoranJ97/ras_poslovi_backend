<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\UserImages;
use App\Provider\ImagePathProvider;
use App\Services\Validations\UserValidationService;
use App\Traits\UserTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    use UserTrait;

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
     * @param $user
     * @param ImagePathProvider $imagePathProvider
     * @return array|false
     */
    public function show($user, ImagePathProvider $imagePathProvider)
    {
        if (!$user) {
            return false;
        }

        return $this->getUserData($user, $imagePathProvider);
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return User
     * @throws \Exception
     */
    public function create(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher
    ): User
    {
        $content = json_decode($request->request->get('request'));


        $userValidationService = new UserValidationService($this->entityManager);
        $errors = $userValidationService->validateUser($request);

        if(count($errors) > 0) {
            throw new \Exception(json_encode($errors));
        }

        $user = new User();
        $user->setEmail($content->email);
        $user->setName($content->name);
        $user->setRoles($content->roles);
        $passwordHashed = $passwordHasher->hashPassword($user, $content->password);
        $user->setPassword($passwordHashed);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    public function update(User $user, Request $request): User
    {
        $content = json_decode($request->request->get('request'));

        $user->setEmail($content->email);
        $user->setName($content->name);
        $user->setRoles($content->roles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function delete($id): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        if (!$user) {
            return ResponseService::sendItem([], 'User does not exist', 404);
            }
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            return ResponseService::sendItem([], 'User deleted');


    }

    public function passwordUpdate(UserPasswordHasherInterface $passwordHasher,
                                   User                        $user,
                                   Request                     $request)
    {
        $content = json_decode($request->getContent());

        $passwordHashed = $passwordHasher->hashPassword($user, $content->password);
        $user->setPassword($passwordHashed);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function removeUserImages(Request $request): void
    {
        $removeUserImages = $request->request->get('deletedImages');
        $this->entityManager->getRepository(UserImages::class)->removeUserImages($removeUserImages);
    }

}
