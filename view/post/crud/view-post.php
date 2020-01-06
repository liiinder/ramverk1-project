<?php

    namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

    $comments = isset($comments) ? $comments : null;
    echo "<h1>var_dump($ comments)</h1>";
    echo "<pre>";
    var_dump($comments);
    echo "</pre>";
    echo "<h1>var_dump($ post)</h1>";
    echo "<pre>";
    var_dump($post);
    echo "</pre>";

?>

<h1><?= $post->title ?></h1>
<p>
    <?= $post->text ?>
</p>
<img src="https://www.gravatar.com/avatar/<?= md5($post->email) ?>?s=100">
<?= $post->username ?>

