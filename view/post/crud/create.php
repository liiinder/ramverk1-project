<?php

namespace Anax\View;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

?><h1>Skriv ett inlägg</h1>

<?= $form ?>

<br>
<a href="<?= url("post") ?>" class="button">Visa alla inlägg</a>

