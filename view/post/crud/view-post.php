<?php

    namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

    $comments = isset($comments) ? $comments : [];
    // var_dump($comments);
?>
<h1><?= $post->title ?></h1>
<p>
    <?= $post->text ?>
</p>
<img src="https://www.gravatar.com/avatar/<?= md5($post->email) ?>?s=100">
<?= $post->username ?>
<a href="<?= url("comment/create/{$post->postId}"); ?>">Reply</a>

<?php foreach ($comments as $comment) :
    echo '<div class="comment-wrapper">';
    for ($i = 0; $i < $comment->depth; $i++) : ?>
        <div class="comment-depth">
    <?php endfor; ?>
        <div class="comment-byline">
            <img src="https://www.gravatar.com/avatar/<?= md5($comment->email) ?>?s=100"><br>
            /<?= $comment->username ?>
        </div>
        <p>
            <?= $comment->text ?> 
        </p>
        <a href="<?= url("comment/create/{$post->postId}?replyId={$comment->commentId}"); ?>">Reply</a>

    <?php for ($i = 0; $i < $comment->depth; $i++) : ?>
        </div>
    <?php endfor; ?>
    </div>
<?php endforeach; ?>
