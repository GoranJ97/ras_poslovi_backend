<?php

namespace App\Controller;

use App\Entity\User;
use App\Provider\ImagePathProvider;
use App\Services\ResponseService;
use App\Services\UserImageService;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class UserController extends AbstractController
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
     * @param UploaderHelper $helper
     * @return JsonResponse
     */
    public function show(Request $request, UploaderHelper $helper): JsonResponse
    {
        $userService = new UserService($this->entityManager);
        $imagePathProvider = new ImagePathProvider($request, $helper);
        $user = $userService->show($this->getUser(), $imagePathProvider);

        return ResponseService::sendItem((array)$user);
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     * @return JsonResponse
     * @throws Exception
     */
    public function create(
        Request                     $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface      $entityManager,
        FormFactoryInterface        $formFactory)
    {
        try {

            $userService = new UserService($this->entityManager);
            $user = $userService->create($request, $passwordHasher);
            $uploadUserImageService = new UserImageService($formFactory, $entityManager);
            $uploadUserImageService->uploadUserImage($request, $user);

            return ResponseService::sendItem([], 'user_created', Response::HTTP_CREATED);
        } catch (Exception $exception) {

            return ResponseService::sendItem(
                [],
                $exception->getMessage(),
                Response::HTTP_BAD_REQUEST
            );

        }
    }

    /**
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @return JsonResponse
     */
    public function update(Request $request, FormFactoryInterface $formFactory): JsonResponse
    {
        $user = $this->getUser();
        $userService = new UserService($this->entityManager);
        $imageService = new UserImageService($formFactory, $this->entityManager);

        try {
            $userService->update($user, $request);
            $imageService->uploadUserImage($request, $user);
            $userService->removeUserImages($request);

            return ResponseService::sendItem([], 'user_updated', Response::HTTP_OK);
        } catch (Exception $exception) {
            return ResponseService::sendItem(
                [],
                'unknown_error',
                Response::HTTP_BAD_REQUEST
            );
        }


    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $userService = new UserService($this->entityManager);

        try {

            $userService->delete($id);

            return ResponseService::sendItem([], 'user_deleted', Response::HTTP_OK);
        } catch (Exception $exception) {
            return ResponseService::sendItem(
                [],
                'unknown_error',
                Response::HTTP_BAD_REQUEST
            );
        }

    }

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param Request $request
     * @return JsonResponse
     */
    public function passwordUpdate(
        UserPasswordHasherInterface $passwordHasher,
        Request                     $request
    ): JsonResponse
    {
        $userService = new UserService($this->entityManager);
        $user = $this->getUser();
        try {
            $userService->passwordUpdate($passwordHasher, $user, $request);

            return ResponseService::sendItem([], 'password_updated', Response::HTTP_OK);
        } catch (Exception $exception) {
            return ResponseService::sendItem(
                [],
                'unknown_error',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

}
