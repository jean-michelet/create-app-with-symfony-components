<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class BlogController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        return new Response(render_view('blog/index.php', ['posts' => $posts]));
    }

    public function show($id): Response
    {
        return new Response(render_view('blog/show.php', ['id' => $id]));
    }
}
