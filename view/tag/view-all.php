<?php

namespace Anax\View;

$tags = isset($tags) ? $tags : null;
?>
<h2>Taggar</h2>
<ul>
<?php foreach ($tags as $tag) : ?>
    <li><a href="tag/view/<?= $tag->tagId ?>">
        <?= $tag->tag . "</a> " . $tag->amount . "st" ?>
    </li>
<?php endforeach; ?>
</ul>