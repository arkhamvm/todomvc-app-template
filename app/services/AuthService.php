<?php

declare(strict_types=1);

namespace App\services;

use App\base\Application;
use Exception;

/**
 * Сервис авторизации
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class AuthService {

	/**
	 * Аутентификация.
	 *
	 * @param string $email     Email
	 * @param string $password  Пароль
	 *
	 * @return bool Успех операции
	 *
	 * @throws Exception
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function login(string $email, string $password): bool {
		$result = Application::$auth->login($email, $password, 1);

		if (false === $result['error']) {
			return true;
		}

		throw new Exception($result['message']);
	}

	/**
	 * Выход.
	 *
	 * @return bool Успех операции
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function logout(): bool {
		return Application::$auth->logout(Application::$auth->getCurrentSessionHash());
	}

	/**
	 * Регистрация.
	 *
	 * @param string $email     Email
	 * @param string $password  Пароль
	 *
	 * @return bool Успех операции
	 *
	 * @throws Exception
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function register(string $email, string $password): bool {
		$result = Application::$auth->register($email, $password, $password);

		if (false === $result['error']) {
			return true;
		}

		throw new Exception($result['message']);
	}
}