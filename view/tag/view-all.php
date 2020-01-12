<?php

namespace Anax\View;

$tags = isset($tags) ? $tags : null;
if ($tags) : ?>
<h4>Det finns <?= sizeof($tags) ?> taggar.</h4>
<ul>
<?php
foreach ($tags as $tag) : ?>
    <li>
        <a href="tag/view/<?= $tag->tagId ?>">
        <?= $tag->tag . "</a> " . $tag->amount . "st" ?>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
<a href="<?= url("tag") ?>" class="button">Visa de mest använda taggarna</a>