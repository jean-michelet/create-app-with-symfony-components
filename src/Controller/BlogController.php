<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class BlogController
{
    public function index(): Response
    {
        return new Response(render_view('blog'));
    }

    public function show($id): Response
    {
        return new Response(render_view('show', ['id' => $id]));
    }
}
