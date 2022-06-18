<?php

namespace App\Traits;

use App\Entity\User;
use App\Provider\ImagePathProvider;

trait UserTrait
{
    /**
     * @param User $user
     * @param ImagePathProvider $imagePathProvider
     * @return array
     */
    protected function getUserData(User $user, ImagePathProvider $imagePathProvider): array
    {

        $userImages = [];

        foreach ($user->getUserImages() as $userImage) {
            array_push($userImages, $imagePathProvider->generatePath($userImage));
        }

        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'description'=> $user->getDescription(),
            'images' => $userImages
        ];
    }

}
