<?php

declare(strict_types=1);

namespace App\base;

/**
 * Роутер для запуска действия в контроллерах.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class Router {

	/**
	 * Обработка запроса.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function handleRequest() {
		$path = Application::$request->getPathInfo();

		preg_match('/^\/(?P<controller>[a-z]+)\/(?P<action>[a-z]+)/ui', $path, $route);

		$route['controller'] ??= 'site';
		$route['action']     ??= 'index';

		$controllerClass = 'App\controllers\\' . ucfirst($route['controller']) . 'Controller';
		$controller      = new $controllerClass();

		$actionName = 'action' . ucfirst($route['action']);
		if (method_exists($controller, $actionName)) {
			$controller->$actionName();
		}
		else {
			Application::$response->setStatusCode(404);
		}
	}
}
