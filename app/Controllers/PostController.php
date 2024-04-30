<?php

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Http\Request;
use Fw\PhpFw\Http\Response;

class PostController extends AbstractController
{

    public function __construct(
        private PostService $postService)
    {
    }

    public function show(int $id)
    {
        $post = $this->postService->find($id);

        return $this->render('post.html.twig', ["post" => $post]);
    }

    public function create()
    {
        return $this->render('create_post.html.twig');
    }

    public function store()
    {
        $post = Post::create(
            $this->request->postData['title'],
            $this->request->postData['body']
        );

        $id = $this->postService->save($post);

        print_r($id);
    }
}