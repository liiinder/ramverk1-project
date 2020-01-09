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
    $edit = " - <a href=" . url("user/edit/" . $user->userId) .">Edit</a>";
}

?>
<h1><?= $user->username ?><?= $edit ?></h1>
<img src="https://www.gravatar.com/avatar/<?= md5($user->email) ?>?s=300">
<p><?= $user->email ?></p>
<h2>Posts</h2>
<ul>
    <?php foreach ($posts as $post) : ?>
    <li>
        <a href="<?= url("post/view/" . $post->postId) ?>"><?= $post->title ?></a>
    </li>
    <?php endforeach; ?>
</ul>
<h2>Comments</h2>
<ul>
    <?php foreach ($comments as $comments) : ?>
    <li>
        <a href="<?= url("post/view/" . $comments->postId) ?>"><?= $comments->title ?></a>
    </li>
    <?php endforeach; ?>
</ul>
