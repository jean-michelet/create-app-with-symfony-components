<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function index($name): Response
    {
        return new Response(render_view('//home/index.php', ['name' => $name]));
    }
}
