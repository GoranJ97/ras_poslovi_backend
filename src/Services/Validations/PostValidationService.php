<?php

namespace App\Services\Validations;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PostValidationService
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

    public function validatePost(Request $request){

        $errors = [];

        if(strlen($request->request->get('title')) <= 3) {
            $errors[] = 'post_title_invalid';
        }

        if(strlen($request->request->get('content')) <= 3) {
            $errors[] = 'post_content_invalid';
        }
        return $errors;

    }

}
