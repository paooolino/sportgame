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

$app = new \Slim\App(["settings" => $config]);

// php renderer
$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer("./templates/");

// Sportgame application
$container['sg'] = new \Paooolino\Sportgame();

// routes
$app->get('/', function (Request $request, Response $response) {
	$template = ["main_layout"];
	$widgets = ["header", "homecontent", "footer"];
	
	$response = $this->view->render($response, "./layout.phtml", [
		"widgets" => $widgets
	]);
	return $response;
});

$app->get('/tools/db-setup', function (Request $request, Response $response) {
	$settings = $this->get("settings");
	if ($settings["development_mode"]) {
		R::nuke();
		$this->sg->initDbTableFromCsv("../dbdata/", "option");
		$this->sg->initDbTableFromCsv("../dbdata/", "league");
		$this->sg->initDbTableFromCsv("../dbdata/", "team");
		$this->sg->initDbTableFromCsv("../dbdata/", "name");
		$this->sg->initDbTableFromCsv("../dbdata/", "surname");
		$this->sg->initDbTableFromCsv("../dbdata/", "country");
		$this->sg->initPlayers();
		$this->sg->initCalendar();
	} else {
		// TO DO send error
	}
	return $response;
});

$app->get('/tools/db-reset', function (Request $request, Response $response) {
});
/*
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
*/

$app->run();
