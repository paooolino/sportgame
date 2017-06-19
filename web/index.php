<?php
/**
 *	index file for the application.
 *	
 *	@uses
 *		\Paooolino\Sportgame
 *			isDatabasePopulated()
 *
 *		templates/main_layout.phtml
 *		templates/widgets/header.phtml
 *		templates/widgets/footer.phtml
 *		templates/widgets/page_home.phtml
 *		templates/widgets/page_tools.phtml
 *		templates/widgets/page_tools_dbsetup.phtml
 *		templates/widgets/page_tools_dbreset.phtml
 */
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
// router to be used in php-view templates
$container['view']->addAttribute('router', $container->get('router'));

// Sportgame application
$container['sg'] = new \Paooolino\Sportgame();

// routes
$app->get('/', function (Request $request, Response $response) {
	$template_name = "main_layout";
	$widgets = ["header", "page_home", "footer"];
	
	$response = $this->view->render($response, "./" . $template_name . ".phtml", [
		"widgets" => $widgets,
		// you can pass any other data to the template file here
		// ...
	]);
	return $response;
})->setName('home');

$app->get('/tools', function (Request $request, Response $response) {
	$template_name = "main_layout";
	$widgets = ["header", "page_tools", "footer"];
	
	$response = $this->view->render($response, "./" . $template_name . ".phtml", [
		"widgets" => $widgets,
	]);
	return $response;	
})->setName('tools');

$app->get('/tools/db-setup', function (Request $request, Response $response) {
	$template_name = "main_layout";
	$widgets = ["header", "page_tools_dbsetup", "footer"];
	
	$isDatabasePopulated = false;
	if (!$this->sg->isDatabasePopulated()) {
		R::nuke();
		$this->sg->initDbTableFromCsv("../dbdata/", "name");
		$this->sg->initDbTableFromCsv("../dbdata/", "surname");
		$this->sg->initDbTableFromCsv("../dbdata/", "league");
		$this->sg->initDbTableFromCsv("../dbdata/", "team");
		$this->sg->initDbTableFromCsv("../dbdata/", "option");
		$isDatabasePopulated = true;
	}
	
	$response = $this->view->render($response, "./" . $template_name . ".phtml", [
		"widgets" => $widgets,
		"isDatabasePopulated" => $isDatabasePopulated
	]);
	return $response;	
})->setName('dbsetup');

$app->get('/tools/db-reset', function (Request $request, Response $response) {
	$template_name = "main_layout";
	$widgets = ["header", "page_tools_dbreset", "footer"];
	
	R::nuke();
	
	$response = $this->view->render($response, "./" . $template_name . ".phtml", [
		"widgets" => $widgets,
	]);
	return $response;	
})->setName('dbreset');

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
