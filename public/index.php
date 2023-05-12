<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('app/Views');
$twig = new \Twig\Environment($loader);

$response = \App\Router::response();

echo $twig->render($response->getTemplate() . '.view.twig', $response->getCharactersCollection());