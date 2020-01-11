<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$posts = isset($posts) ? $posts : null;
$tags = isset($tags) ? $tags : [];

// Create urls for navigation
$urlToCreate = url("post/create");

?>
<p>
    <a href="<?= $urlToCreate ?>" class="button">Skapa nytt inlägg</a>
</p>

<?php if (!$posts) : ?>
    <p>Det finns inga inlägg</p>
<?php
    return;
endif;
?>

<?php foreach ($posts as $post) : ?>
<div class="clearfix">
    <h4>
        <?php if ($userId == $post->userId) : ?>
            <a href="<?= url("post/update/{$post->postId}"); ?>" class="button right">Ändra</a>
        <?php endif; ?>
        <a href="<?= url("post/view/{$post->postId}"); ?>">
            <?= $post->title ?>
        </a>
    </h4>
    <div class="right clearfix">
        <a href="<?= url("user/profile/" . $post->userId)?>">
            <img src="https://www.gravatar.com/avatar/<?= md5($post->email) ?>?s=100"><br>
            <?= $post->username?>
        </a>
    </div>
    <p>
        <?= $post->text ?>
    </p>
    <p>
        <?php foreach ($tags as $tag) : 
            if ($tag->postId == $post->postId) : ?>
                &nbsp;<a href="<?= url("tag/view/{$tag->tagId}"); ?>"><?= $tag->tag ?></a>
            <?php endif;
        endforeach; ?>
    </p>
    <a href="<?= url("comment/create/{$post->postId}"); ?>" class="button">Svara</a>
</div>

<?php endforeach; ?>
