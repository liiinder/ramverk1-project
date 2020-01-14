<?php
    namespace Anax\View;

    $comments = isset($comments) ? $comments : [];
    $tags = isset($tags) ? $tags : [];
?>
<div class="clearfix">
    <h2>
        <?php if ($userId == $post->userId) : ?>
            <a href="<?= url("post/update/{$post->postId}"); ?>" class="button right">Ändra</a>
        <?php endif; ?>
        <?= $post->title ?>
    </h2>
    <div class="right clearfix">
        <a href="<?= url("user/profile/" . $post->userId)?>">
            <img src="https://www.gravatar.com/avatar/<?= md5($post->email) ?>?s=100&d=mm"><br>
            <?= $post->username?>
        </a>
    </div>
    <p>
        <?= $filter->defaultTransform($post->text) ?>
    </p>
    <p>
        <?php foreach ($tags as $tag) : ?> 
            <a href="<?= url("tag/view/{$tag->tagId}"); ?>"><?= $tag->tag ?></a>
        <?php endforeach; ?>
    </p>
    <a href="<?= url("comment/create/{$post->postId}"); ?>" class="button">Svara</a>
</div>
<h2>Kommentarer</h2>
<?php foreach ($comments as $comment) :
    $wrapper = ($comment->depth == 1) ? " comment-wrapper" : "";
    echo "<div class='clearfix'>";
    for ($i = 0; $i < $comment->depth; $i++) : ?>
        <div class='comment-depth<?= $wrapper ?>'>
    <?php endfor; ?>
        <div class="right clearfix">
            <a href="<?= url("user/profile/" . $comment->userId)?>">
                <img src="https://www.gravatar.com/avatar/<?= md5($comment->email) ?>?s=50&d=mm"><br>
                <?= $comment->username ?>
            </a>
        </div>
        <p>
            <?= $filter->defaultTransform($comment->text) ?> 
        </p>
        <?php if ($userId == $comment->userId) : ?>
            <a href="<?= url("comment/update/{$comment->commentId}"); ?>" class="button">Ändra</a>
        <?php endif; ?>
        <a href="<?=
            url("comment/create/{$post->postId}?replyId={$comment->commentId}");
            ?>" class="button">
            Svara
        </a>

    <?php for ($i = 0; $i < $comment->depth; $i++) : ?>
        </div>
    <?php endfor; ?>
    </div>
    <?php endforeach; ?>
<br>
<a href="<?= url("post") ?>" class="button">Visa alla inlägg</a>