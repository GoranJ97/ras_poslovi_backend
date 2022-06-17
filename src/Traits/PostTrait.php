<?php

namespace App\Traits;

use App\Entity\Post;

trait PostTrait
{
    protected function getPostData(Post $post)
    {
        $user = $post->getUser();
        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'user' => [
               'id' => $user->getId(),
               'name' => $user->getName(),
               'images' => $user->getUserImages()
            ]
        ];
    }

}
