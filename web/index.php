<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

// db connection
class_alias('\RedBeanPHP\R','\R');
R::setup('mysql:host=localhost;dbname=sportgame', 'root', 'root');
				
// slim application
$config = [];
// Slim Framework configuration
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
// custom configuration
// when false, db setup is not permitted
$config['development_mode'] = true;

$app = new \Slim\App(["settings" => $config]);

// php renderer
$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer("./templates/");

// Sportgame application
$sg = new \Paooolino\Sportgame();

// routes
$app->get('/', function (Request $request, Response $response) {
	$widgets = ["login", "register", "standings", "events"];
	$response = $this->view->render($response, "./layout.phtml", ["widgets" => $widgets]);
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

$app->get('/tools/db-setup', function (Request $request, Response $response) {
	$settings = $this->get("settings");
	if ($settings["development_mode"]) {
		$sg->initDbTable("../dbdata/leagues.csv");
	} else {
		// TO DO send error
	}
	/*
	$file = fopen('myCSVFile.csv', 'r');
	while (($line = fgetcsv($file)) !== FALSE) {
		//$line is an array of the csv elements
		print_r($line);
	}
	fclose($file);
	*/
	return $response;
});

$app->run();
