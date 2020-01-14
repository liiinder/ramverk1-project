<?php

namespace Anax\View;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$posts = isset($posts) ? $posts : null;
$comments = isset($comments) ? $comments : null;
$user = isset($user) ? $user : null;
$edit = "";
if ($active) {
    $edit = " <a href=" . url("user/edit/" . $user->userId) ." class='button'>Ändra</a>";
}

?>
<h1>
    <?= $user->username ?>
    <?= $edit ?>
</h1>
<div class="right">
<img src="https://www.gravatar.com/avatar/<?= md5($user->email) ?>?s=300&d=mm">
<p><?= $user->email ?></p>
</div>
<?php if ($user->bio) : ?>
<p><?= $user->bio ?></p>
<?php endif;
if ($posts) : ?>
<div class="left marginright">
    <h4><?= sizeof($posts) ?>st inlägg</h4>
    <ul>
        <?php foreach ($posts as $post) : ?>
        <li>
            <a href="<?= url("post/view/" . $post->postId) ?>"><?= $post->title ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif;
if ($comments) : ?>
<div class="left">
    <h4><?= sizeof($comments) ?>st kommentar<?= (sizeof($comments) > 1) ? "er" : "" ?></h4>
    <ul>
        <?php foreach ($comments as $comments) : ?>
        <li>
            <a href="<?= url("post/view/" . $comments->postId) ?>"><?= $comments->title ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif;