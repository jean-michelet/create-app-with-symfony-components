<?php

namespace App\Controller;

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
        return new Response(render_view('blog'));
    }

    public function new(): Response
    {
        dd('my entity  manager', $this->entityManager);
        return new Response('OK');
    }

    public function show($id): Response
    {
        return new Response(render_view('show', ['id' => $id]));
    }
}
