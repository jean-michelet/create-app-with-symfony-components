<?php

use App\Entity\Post;
use App\Entity\User;

require_once __DIR__.'/_boostrap-fixtures.php';

try {
    $users = $em->getRepository(User::class)->findAll();
    foreach ($users as $user) {
        $em->remove($user);
    }

    $posts = $em->getRepository(Post::class)->findAll();
    foreach ($posts as $post) {
        $em->remove($post);
    }

    $em->flush();
} catch (Exception $e) {
    echo $e;
}
