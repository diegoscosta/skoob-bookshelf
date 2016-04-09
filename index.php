<?php
require_once 'vendor/autoload.php';

$app = new Silex\Application(); 

$bookshelf = new DiegoSCosta\Skoob\Bookshelf();

$app->get('/{user}/{shelf}/{all}', function($user, $shelf, $all) use ($app, $bookshelf) {

	$bookshelf->setUser($user);
	$bookshelf->setShelf($shelf);

	$result = ($all) ? $bookshelf->getBooks() : $bookshelf->getRandomBook();

	return $app->json($result);

})->value('shelf', '3')->value('all', '0');

$app->run(); 