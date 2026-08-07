<?php

return [
    'fullname' => env('SITE_FULLNAME', 'Any Company Ltd'),
    'address' => env('SITE_ADDRESS', 'Anytown, Anystreet, Anytown, AN1 1AN'),
    'telephone' => env('SITE_TELEPHONE', '01704 000000'),
    'email' => env('SITE_EMAIL', 'test@example.com'),
    'charity_number' => env('SITE_CHARITY_NUMBER', '123456789'),
    'established' => env('SITE_ESTABLISHED', '2026'),
    'opening_times' => "Monday - Thursday: 9:00am - 4:00pm",

    'nav_links' => [
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'About', 'href' => '#about'],
        ['label' => 'Services', 'href' => '#services'],
        ['label' => 'Events', 'href' => '#events'],
        ['label' => 'News', 'href' => '/news-updates'],
        ['label' => 'Contact', 'href' => '#contact'],
    ],

    'robots_allowed' => env('ROBOTS_ALLOWED', false),
];
