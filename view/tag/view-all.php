<?php

namespace Anax\View;

$tags = isset($tags) ? $tags : null;
$comments = isset($comments) ? $comments : null;
$posts = isset($posts) ? $posts : null;

if ($tags) :
?>
<h2>Dom <?= sizeof($tags) ?> mest använda taggarna</h2>
<ul>
<?php foreach ($tags as $tag) : ?>
    <li><a href="tag/view/<?= $tag->tagId ?>">
        <?= $tag->tag . "</a> " . $tag->amount . "st" ?>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; 
if ($posts) : ?>
<h2>Dom <?= sizeof($posts) ?> mest aktiva inläggsskrivare</h2>
<?php foreach ($posts as $user) : ?>
    <a href="user/profile/<?= $user->userId ?>">
        <img src="https://www.gravatar.com/avatar/<?= md5($user->email) ?>?s=100"><br>
        <?= $user->username . "</a> " . $user->amount . "st" ?>
    </a><br>
<?php endforeach;
endif;
if ($comments) : ?>
<h2>Dom <?= sizeof($comments) ?> mest aktiva kommenterarna</h2>
<?php foreach ($comments as $user) : ?>
    <a href="user/profile/<?= $user->userId ?>">
        <img src="https://www.gravatar.com/avatar/<?= md5($user->email) ?>?s=100"><br>
        <?= $user->username . "</a> " . $user->amount . "st" ?>
    </a><br>
<?php endforeach;
endif;
