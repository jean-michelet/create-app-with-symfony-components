<?php

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;

require_once __DIR__.'/_boostrap-fixtures.php';

require_once __DIR__.'/remove.php';


try {
    $comments = [];
    foreach (['patrick', 'jean', 'julie'] as $name) {
        $user = new User();
        $user->setEmail($name.'@mail.com');

        foreach (['Very cool!', 'This article is terrible!', 'Amazing article!'] as $content) {
            $comments[] = $comment = new Comment();
            $comment->setContent('By '.$name.' '.$content);
            $comment->setAuthor($user);
        }

        $em->persist($user);
    }

    $offset = 0;
    foreach (['Cats and Dog', 'Cooking cookies!', 'Awesome news!'] as $key => $title) {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent('Content_'.$key);

        for ($i = $offset; $i < 3 + $offset; $i++) {
            $comments[$i]->setPost($post);
        }
        $offset += 3;

        // $comment[$key]
        // lier des commentaires à ces posts
        // Post to comments OneToMany One post has many comments

        $em->persist($post);
    }

    $em->flush();
} catch (Exception $e) {
    echo $e;
}
