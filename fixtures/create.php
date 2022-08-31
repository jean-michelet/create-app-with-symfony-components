<?php

use App\Entity\Post;
use App\Entity\User;

require_once __DIR__.'/_boostrap-fixtures.php';

try {
    foreach (['patrick', 'jean', 'julie'] as $name) {
        $user = new User();
        $user->setEmail($name.'@mail.com');

        $em->persist($user);
    }

    foreach (['Cats and Dog', 'Cooking cookies!', 'Awesoem news!'] as $key => $title) {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent('Content_'.$key);

        $em->persist($post);
    }

    $em->flush();
} catch (Exception $e) {
    echo $e;
}
