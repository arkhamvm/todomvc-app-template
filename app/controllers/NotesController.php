<?php

declare(strict_types=1);

namespace App\controllers;

use App\base\Application;
use App\services\NotesService;
use App\views\layouts\DefaultLayout;
use App\views\pages\NotesPage;
use InvalidArgumentException;
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
	public const PARAM_DESCRIPTION = 'description';

	public function __construct() {
		$this->service = new NotesService();
	}

	/**
	 * Главная страница списка заметок.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionIndex() {
		if (false === Application::$auth->isLogged()) {
			Application::$response->setStatusCode(302);
			Application::$response->headers->set('Location', '/auth/index');

			return;
		}

		try {
			Application::$response->setContent((string)(new DefaultLayout(
				(string)(new NotesPage())
			)));
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent((DEBUG ? $e->getMessage() : 'Неверный запрос'));
		}
	}

	/**
	 * Действие получения списка заметок.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionGet() {
		if (false === Application::$auth->isLogged()) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent('Auth Required');

			return;
		}

		try {
			Application::$response->headers->set('Content-Type', 'application/json');
			Application::$response->setContent(json_encode($this->service->getByUser()));
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent((DEBUG ? $e->getMessage() : 'Неверный запрос'));
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

		$description = trim(Application::$request->request->get(static::PARAM_DESCRIPTION, ''));

		try {
			if (empty($description) || strlen($description) > 1000) {
				throw new InvalidArgumentException('Неверный текст');
			}

			$this->service->create($description);
		}
		catch (InvalidArgumentException $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent((DEBUG ? $e->getMessage() : 'Неверный запрос'));
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

		$id          = (int)Application::$request->request->get(static::PARAM_ID, 0);
		$description = trim(Application::$request->request->get(static::PARAM_DESCRIPTION, ''));

		try {
			if (0 >= $id || $id > PHP_INT_MAX) {
				throw new InvalidArgumentException('Неверный идентификатор');
			}

			if (empty($description) || strlen($description) > 1000) {
				throw new InvalidArgumentException('Неверный текст');
			}

			$this->service->update($id, $description);
		}
		catch (InvalidArgumentException $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent((DEBUG ? $e->getMessage() : 'Неверный запрос'));
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

		$ids = Application::$request->request->get(static::PARAM_IDS, []);
		$ids = array_filter($ids, 'is_numeric');

		try {
			if (false === is_array($ids) || 0 === count($ids)) {
				throw new InvalidArgumentException('Нет идентификаторов');
			}

			$this->service->toggleCompleted($ids);
		}
		catch (InvalidArgumentException $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent((DEBUG ? $e->getMessage() : 'Неверный запрос'));
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

		$ids = Application::$request->request->get(static::PARAM_IDS, []);
		$ids = array_filter($ids, 'is_numeric');

		try {
			if (false === is_array($ids) || 0 === count($ids)) {
				throw new InvalidArgumentException('Нет идентификаторов');
			}

			$this->service->delete($ids);
		}
		catch (InvalidArgumentException $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent((DEBUG ? $e->getMessage() : 'Неверный запрос'));
		}
	}
}
