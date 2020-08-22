<?php

declare(strict_types=1);

namespace App\controllers;

use App\base\Application;
use App\services\AuthService;
use App\services\NotesService;
use Throwable;

/**
 * Контроллер по умолчанию.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class SiteController {
	/**
	 * Главная страница.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionIndex() {
		Application::$response->setStatusCode(302);
		Application::$response->headers->set('Location', '/notes/index');
	}
}
