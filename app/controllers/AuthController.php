<?php

declare(strict_types=1);

namespace App\controllers;

use App\base\Application;
use App\services\AuthService;
use App\views\layouts\DefaultLayout;
use App\views\pages\AuthPage;
use Throwable;

/**
 * Контроллер аутентификации.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class AuthController {
	/** Сервис аутентификации */
	protected ?AuthService $service = null;

	public const PARAM_EMAIL           = 'email';
	public const PARAM_PASSWORD        = 'password';
	public const PARAM_PASSWORD_REPEAT = 'password_repeat';

	public function __construct() {
		$this->service = new AuthService();
	}

	/**
	 * Страница входа/регистрации.
	 *
	 * @param string|null $error Ошибка
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionIndex(?string $error = null) {
		Application::$response->setContent((string)(new DefaultLayout(
			(string)(new AuthPage($error))
		)));
	}

	/**
	 * Действие регистрации.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionRegister() {
		$email          = Application::$request->request->get(static::PARAM_EMAIL) ?? '';
		$password       = Application::$request->request->get(static::PARAM_PASSWORD) ?? '';
		$passwordRepeat = Application::$request->request->get(static::PARAM_PASSWORD_REPEAT) ?? '';

		try {
			$this->service->register($email, $password, $passwordRepeat);
			$this->service->login($email, $password);

			Application::$response->setStatusCode(302);
			Application::$response->headers->set('Location', '/');
		}
		catch (Throwable $e) {
			$this->actionIndex($e->getMessage());
		}
	}

	/**
	 * Действие входа.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function actionLogin() {
		$email    = Application::$request->request->get(static::PARAM_EMAIL) ?? '';
		$password = Application::$request->request->get(static::PARAM_PASSWORD) ?? '';

		try {
			$this->service->login($email, $password);

			Application::$response->setStatusCode(302);
			Application::$response->headers->set('Location', '/');
		}
		catch (Throwable $e) {
			$this->actionIndex($e->getMessage());
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

			Application::$response->setStatusCode(302);
			Application::$response->headers->set('Location', '/');
		}
		catch (Throwable $e) {
			$this->actionIndex($e->getMessage());
		}
	}
}
