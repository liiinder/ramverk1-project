<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("post/create");
$urlToDelete = url("post/delete");



?><h1>View all items</h1>

<p>
    <a href="<?= $urlToCreate ?>">Create</a> | 
    <a href="<?= $urlToDelete ?>">Delete</a>
</p>

<?php if (!$items) : ?>
    <p>There are no items to show.</p>
<?php
    return;
endif;
?>

<?php foreach ($items as $item) : ?>
<div>
    <h2>
        <a href="<?= url("post/view/{$item->id}"); ?>">
            <?= $item->title ?>
        </a>
        <?php if ($user) : ?>
            <a href="<?= url("post/update/{$item->id}"); ?>">EDIT</a>
        <?php endif; ?>
    </h2>
    <p><?= $item->text ?></p>
</div>
<?php endforeach; ?>
