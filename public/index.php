<?php

if (PHP_SAPI == 'cli-server') {
	$url  = parse_url($_SERVER['REQUEST_URI']);
	$file = __DIR__ . $url['path'];
	if (is_file($file)) {
		return false;
	}
}

date_default_timezone_set('America/Los_Angeles');

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => false, // Set to false in production.
		'addContentLengthHeader' => false,
		'determineRouteBeforeAppMiddleware' => false,
		'renderer' => [
			'template_path' => __DIR__ . '/../templates',
		],
		'twig' => [
			'template_path' => __DIR__ . '/../templates',
			'environment' => [
				'auto_reload' => true,
				'autoescape' => true,
				'cache' => __DIR__ . '/../templates/cache', // false,
				'charset' => 'utf-8',
				'strict_variables' => false,
				'debug' => true,
			]
		],
	],
]);

$container = $app->getContainer();

$container['view'] = function($c) {
	
	$settings = $c->get('settings')['twig'];
	$template_path = $settings['template_path'];
	
	$view = new \Slim\Views\Twig($template_path, $settings['environment']);
	
	$view->addExtension(new Twig_Extension_Debug());
	
	$view->addExtension(
		new \Slim\Views\TwigExtension(
			$c['router'],
			rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/')
		)
	);
	
	$view->getEnvironment()->addGlobal('current_path', trim($c['request']->getUri()->getPath(), '/'));
	
	return $view;
	
};

$controller = function($request, $response, $args) {
	
	$template = (isset($args['path']) ? $args['path'] : 'home');
	
	return $this->view->render($response, 'site/' . $template . '.phtml', array(
		'template' => $template,
		'path' => str_replace('home', '', $template),
		'route' => $request->getAttribute('route')->getName(),
	));
	
};

$app->get('/', $controller)->setName('home');
$app->get('/{path:about|contact}/', $controller)->setName('page');

$app->run();
