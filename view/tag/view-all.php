<?php

namespace Anax\View;

$tags = isset($tags) ? $tags : null;
$comments = isset($comments) ? $comments : null;
$posts = isset($posts) ? $posts : null;

if ($tags) :
?>
<h4>De <?= sizeof($tags) ?> mest använda taggarna</h4>
<ul>
<?php foreach ($tags as $tag) : ?>
    <li><a href="tag/view/<?= $tag->tagId ?>">
        <?= $tag->tag . "</a> " . $tag->amount . "st" ?>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; 
if ($posts) : ?>
    <h4>De <?= sizeof($posts) ?> mest aktiva inläggsskrivarna</h4>
    <?php foreach ($posts as $user) : ?>
        <div class="toplist">
            <a href="user/profile/<?= $user->userId ?>">
                <img src="https://www.gravatar.com/avatar/<?= md5($user->email) ?>?s=100"><br>
                <?= $user->username . "</a> " . $user->amount . "st" ?>
            </a><br>
        </div>
    <?php endforeach;
endif;
if ($comments) : ?>
    <h4>De <?= sizeof($comments) ?> mest aktiva kommenterarna</h4>
    <?php foreach ($comments as $user) : ?>
        <div class="toplist">
            <a href="user/profile/<?= $user->userId ?>">
                <img src="https://www.gravatar.com/avatar/<?= md5($user->email) ?>?s=100"><br>
                <?= $user->username . "</a> " . $user->amount . "st" ?>
            </a><br>
        </div>
<?php endforeach; ?>
<?php endif;
