<?php

use App\Entity\Post;

require_once __DIR__.'/_boostrap-fixtures.php';

try {
    $dql = "SELECT p FROM App\Entity\Post p 
        JOIN p.comments c 
        JOIN c.author u
    ";

    $query = $em->createQuery($dql);
    $posts = $em->getRepository(Post::class)->findPostsWithCommentsAndAuthors();

    foreach ($posts as $post) {
        foreach ($post->getComments() as $comment) {
            $comment->getAuthor()->getEmail();
        }
    }
} catch (Exception $e) {
    echo $e;
}
