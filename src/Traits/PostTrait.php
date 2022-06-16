<?php

namespace App\Traits;

use App\Entity\Post;

trait PostTrait
{
    protected function getPostData(Post $post)
    {

        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'user' => $post->getUser()

        ];
    }

}
