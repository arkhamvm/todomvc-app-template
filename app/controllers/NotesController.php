<?php

declare(strict_types=1);

namespace App\controllers;

use App\base\Application;
use App\services\AuthService;
use App\services\NotesService;
use Throwable;

/**
 * Контроллер заметок.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class NotesController {
	/** Сервис заметок */
	protected ?NotesService $service = null;

	public const PARAM_ID          = 'id';
	public const PARAM_IDS         = 'ids';
	public const PARAM_DESCRIPTION = 'password';

	public function __construct() {
		$this->service = new NotesService();
	}

	/**
	 * Действие получения списка заметок.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionIndex() {
		if (false === Application::$auth->isLogged()) {
			Application::$response->setStatusCode(301);
			Application::$response->headers->set('Location', '/auth/index');

			return;
		}

		try {
			Application::$response->headers->set('Content-Type', 'application/json');
			Application::$response->setContent(json_encode($this->service->getByUser()));
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
	}

	/**
	 * Действие регистрации.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionCreate() {
		if (false === Application::$auth->isLogged()) {
			Application::$response->setStatusCode(403);

			return;
		}

		$description = Application::$request->request->get(static::PARAM_DESCRIPTION);

		try {
			$this->service->create($description);
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
	}

	/**
	 * Действие обновления текста заметки.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionUpdate() {
		if (false === Application::$auth->isLogged()) {
			Application::$response->setStatusCode(403);

			return;
		}

		$id          = Application::$request->request->get(static::PARAM_ID);
		$description = Application::$request->request->get(static::PARAM_DESCRIPTION);

		try {
			$this->service->update($id, $description);
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
	}

	/**
	 * Действие обновления текста заметки.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionToggle() {
		if (false === Application::$auth->isLogged()) {
			Application::$response->setStatusCode(403);

			return;
		}

		$ids = Application::$request->request->get(static::PARAM_IDS);

		try {
			$this->service->toggleCompleted($ids);
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
	}

	/**
	 * Действие обновления текста заметки.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionDelete() {
		if (false === Application::$auth->isLogged()) {
			Application::$response->setStatusCode(403);

			return;
		}

		$id = Application::$request->request->get(static::PARAM_ID);

		try {
			$this->service->delete($id);
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
	}
}
