<?php
/**
 * Supply the basis for the navbar as an array.
 */
global $di;

if ($di->get("session")->has("username")) {
    $text = "Profil";
    // $url = "user/edit";
} else {
    $text = "Login";
    // $url = "user/login";
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
        // [
        //     "text" => "Redovisning",
        //     "url" => "redovisning",
        //     "title" => "Redovisningstexter från kursmomenten.",
        //     "submenu" => [
        //         "items" => [
        //             [
        //                 "text" => "Kmom01",
        //                 "url" => "redovisning/kmom01",
        //                 "title" => "Redovisning för kmom01.",
        //             ],
        //             [
        //                 "text" => "Kmom02",
        //                 "url" => "redovisning/kmom02",
        //                 "title" => "Redovisning för kmom02.",
        //             ],
        //         ],
        //     ],
        // ],
        // [
        //     "text" => "Om",
        //     "url" => "om",
        //     "title" => "Om denna webbplats.",
        // ],
        [
            "text" => "Forum",
            "url" => "post",
            "title" => "forum"
        ],
        [
            "text" => $text,
            "url" => "user/edit",
            "title" => "user",
        ],
        // [
        //     "text" => "Verktyg",
        //     "url" => "verktyg",
        //     "title" => "Verktyg och möjligheter för utveckling.",
        // ],
    ],
];
