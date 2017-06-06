<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;
$container['view'] = new \Slim\Views\PhpRenderer("./templates/");

$app->get('/', function (Request $request, Response $response) {
	$widgets = ["login", "register", "standings", "events"];
	$response = $this->view->render($response, "layout.phtml", ["widgets" => $widgets]);
	return $response;
});

$app->get('/leagues', function (Request $request, Response $response) {
	$widgets = ["abstract"];
	$response = $this->view->render($response, "layout.phtml", ["widgets" => $widgets]);
	return $response;
});

$app->get('/league/{league_id}', function (Request $request, Response $response) {
	$widgets = ["leagueinfo", "standings", "calendar"];
	$response = $this->view->render($response, "layout.phtml", ["widgets" => $widgets]);
	return $response;
});

$app->get('/team/{team_id}', function (Request $request, Response $response) {
	$widgets = ["teamfile"];
	$response = $this->view->render($response, "layout.phtml", ["widgets" => $widgets]);
	return $response;
});

$app->get('/player/{player_id}', function (Request $request, Response $response) {
	$widgets = ["playerfile"];
	$response = $this->view->render($response, "layout.phtml", ["widgets" => $widgets]);
	return $response;
});

$app->run();
