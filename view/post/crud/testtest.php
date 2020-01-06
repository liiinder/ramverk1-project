<?php foreach ($comments as $comment) : ?>
<div>
    <p>
        <?= $comment->text ?> /<?= $comment->username?>
        <img src="https://www.gravatar.com/avatar/<?= md5($comment->email) ?>?s=100">
    </p>
</div>

<?php endforeach;