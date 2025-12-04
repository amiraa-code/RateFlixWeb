<?php
require_once __DIR__ . '/../../vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader(__DIR__);
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'autoescape' => 'html',
]);
return $twig;
