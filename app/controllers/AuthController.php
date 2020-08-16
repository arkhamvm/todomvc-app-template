<?php

declare(strict_types=1);

namespace App\controllers;

use App\base\Application;
use App\services\AuthService;
use Throwable;

/**
 * Контроллер аутентификации.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class AuthController {
	/** Сервис аутентификации */
	protected ?AuthService $service = null;

	public const PARAM_EMAIL    = 'email';
	public const PARAM_PASSWORD = 'password';

	public function __construct() {
		$this->service = new AuthService();
	}

	/**
	 * Действие регистрации.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionRegister() {
		$email    = Application::$request->request->get(static::PARAM_EMAIL);
		$password = Application::$request->request->get(static::PARAM_PASSWORD);

		try {
			$this->service->register($email, $password);
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
	}

	/**
	 * Действие входа.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionLogin() {
		$email    = Application::$request->request->get(static::PARAM_EMAIL);
		$password = Application::$request->request->get(static::PARAM_PASSWORD);

		try {
			$this->service->login($email, $password);
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
	}

	/**
	 * Действие выхода.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionLogout() {
		try {
			$this->service->logout();
		}
		catch (Throwable $e) {
			Application::$response->setStatusCode(400);
			Application::$response->setContent($e->getMessage());
		}
	}
}
