<?php

use linder\User\User;
/**
 * Supply the basis for the navbar as an array.
 */
global $di;

$userId = $di->get("session")->get("userId");
$user = new User();
$user->setDb($di->get("dbqb"));
$user->find("userId", $userId);
if ($userId) {
    $text = 'Profil <img src="https://www.gravatar.com/avatar/' . md5($user->email) . '?s=50">';
    $url = "user/profile/" . $userId;
} else {
    $text = "Login";
    $url = "user/login";
}
return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",
 
    // Here comes the menu items
    "items" => [
        [
            "text" => "Hem",
            "url" => "",
            "title" => "Första sidan, börja här.",
        ],
        [
            "text" => "Forum",
            "url" => "post",
            "title" => "forum"
        ],
        [
            "text" => $text,
            "url" => $url,
            "title" => "user",
        ],
    ],
];
