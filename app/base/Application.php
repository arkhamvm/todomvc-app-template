<?php

declare(strict_types=1);

namespace App\base;

use PDO;
use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Базовый класс приложения.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class Application {
	/** Подключеник к PDO */
	public static ?PDO $db = null;
	/** Бибилиотека авторизации */
	public static ?PHPAuth $auth = null;
	/** Модель входящего запроса */
	public static ?Request $request = null;
	/** Модель исходящего ответа */
	public static ?Response $response = null;
	/** Конфигурация приложения */
	public static array $config = [];

	/** Роутер */
	protected ?Router $router = null;

	public function __construct() {
		$this->router = new Router();
	}

	/**
	 * Запуск приложения.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function run() {
		$this->loadConfig();
		$this->initDb();
		$this->initAuth();

		static::$request  = Request::createFromGlobals();
		static::$response = new Response;

		$this->router->handleRequest();

		static::$response->send();
	}

	/**
	 * Загрузка конфигурации.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	protected function loadConfig() {
		static::$config = array_merge(
			require (APP_ROOT . '/config/common.php'),
		);
	}

	/**
	 * Инициализация PDO.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	protected function initDb() {
		static::$db = new PDO(
			static::$config['db']['dsn'],
			static::$config['db']['username'],
			static::$config['db']['password'],
			[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
		);
	}

	/**
	 * Инициализация PHPAuth.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	protected function initAuth() {
		static::$auth = new PHPAuth(
			static::$db,
			new PHPAuthConfig(static::$db)
		);
	}
}
