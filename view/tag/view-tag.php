<?php

namespace Anax\View;

$tags = isset($tags) ? $tags : null;
var_dump($test);
?>
<h1>
    Inlägg med taggen: <?= $tags[0]->tag ?>
</h1>
<?php foreach ($tags as $tag) : ?>
<a href="<?= url('post/view/' . $tag->postId ) ?>">
    <h4><?= $tag->title ?></h4>
</a>
<?php endforeach;