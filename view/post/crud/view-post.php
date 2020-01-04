<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

    $comments = isset($comments) ? $comments : null;
    var_dump($comments);
?>

<h1><?= $post->title ?></h1>
<p>
    <?= $post->text ?>
</p>
    <img src="https://www.gravatar.com/avatar/<?= md5($post->email) ?>?s=100">
    <?= $post->username ?>

<?php foreach ($comments as $comment) : ?>
<div>
    <p>
        <?= $comment->text ?> /<?= $comment->username?>
        <img src="https://www.gravatar.com/avatar/<?= md5($comment->email) ?>?s=100">
    </p>
</div>

<?php endforeach; ?>
