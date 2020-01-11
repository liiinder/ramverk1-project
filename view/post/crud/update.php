<?php

namespace Anax\View;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$item = isset($item) ? $item : null;
$type = isset($type) ? $type : "";

?><h1>Redigera <?= $type ?></h1>

<?= $form ?>
<br>
<a href="<?= url("post") ?>" class="button">Visa alla inlägg</a>
