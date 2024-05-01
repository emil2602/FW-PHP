<?php

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Http\RedirectResponse;
use Fw\PhpFw\Http\Request;
use Fw\PhpFw\Http\Response;
use Fw\PhpFw\Session\SessionInterface;

class PostController extends AbstractController
{

    public function __construct(
        private PostService $postService,
        private SessionInterface $session
    )
    {
    }

    public function show(int $id)
    {
        $post = $this->postService->find($id);

        $this->session->setFlash('success', 'Post successfully created');
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

        $post = $this->postService->save($post);

        return new RedirectResponse("/posts/{$post->getId()}");
    }
}