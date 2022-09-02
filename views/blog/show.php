<?php $extends = 'base.php' ?>

<div class="container">
    <h1><?php echo $post->getTitle() ?></h1>
    <!-- Afficher l'article et ses commentaires (avec auteur) -->
    <?php $count = $post->getComments()->count() ?>
    <h3>Comments (<?php echo $count ?>)</h3>
    <div class="row">
        <?php foreach ($post->getComments() as $comment): ?>
            <div class="col-lg-12 my-2">
                <div class="card">
                    <div class="card-header">
                        Author: <?php echo $comment->getAuthor()->getEmail() ?>
                    </div>
                    <div class="card-body">
                        <p><?php echo $comment->getContent() ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
