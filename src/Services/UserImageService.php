<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\UserImages;
use App\Form\MultipleImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserImageService
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    public function uploadUserImage(Request $request, User $user) {
        $form = $this->formFactory->create(MultipleImageType::class);
        $form->handleRequest($request);

        $images = $request->files->get('images');

        if($images) {
            foreach ($images as $image) {
                $userImage = new UserImages();
                $userImage->setFile($image);
                $userImage->setUser($user);
                $this->entityManager->persist($userImage);
            }
        }

        $this->entityManager->flush();
    }

}
