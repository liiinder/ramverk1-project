<?php
/**
 * Supply the basis for the navbar as an array.
 */

$userId = $di->get("session")->get("userId");

$menu = [
    // Use for styling the menu
    "id" => "rm-menu",
    "wrapper" => null,
    "class" => "rm-default rm-mobile",

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
            "text" => "Om",
            "url" => "om",
            "title" => "Om projektet"
            ]
        ],
    ];

$login = [
    "text" => "Logga in",
    "url" => "user/login",
    "title" => "Login"
];
if ($userId) {
    $profil = [
        "text" => 'Profil',
        "url" => "user/profile/" . $userId,
        "title" => "Profil",
    ];
    $login = [
        "text" => "Logga ut",
        "url" => "user/logout",
        "title" => "Login"
    ];
    array_push($menu["items"], $profil);
};
array_push($menu["items"], $login);

return $menu;
