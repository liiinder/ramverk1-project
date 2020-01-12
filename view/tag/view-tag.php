<?php

namespace Anax\View;

$tags = isset($tags) ? $tags : null;
?>
<h1>
    Inlägg med taggen: <?= $tags[0]->tag ?>
</h1>
<?php foreach ($tags as $tag) : ?>
<a href="<?= url('post/view/' . $tag->postId ) ?>">
    <h4><?= $tag->title ?></h4>
</a>
<?php endforeach; ?>
<a href="<?= url("tag/all") ?>" class="button">Visa alla taggar</a>
<a href="<?= url("tag") ?>" class="button">Visa de mest använda taggarna</a>