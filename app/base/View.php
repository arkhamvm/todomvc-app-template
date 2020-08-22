<?php

declare(strict_types=1);

namespace App\base;

use Throwable;

/**
 * Базовый класс для шаблонов.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
abstract class View {

	/**
	 * Получение функции для отрисовки контента.
	 *
	 * @return callable Функция для отрисовки контента
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	abstract public function getRenderer(): callable;

	/**
	 * Получение отрисованного контента.
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public final function __toString() {
		ob_start();
		ob_implicit_flush(0);

		$renderer = $this->getRenderer();

		try {
			$renderer();
			return ob_get_clean();
		}
		catch (Throwable $e) {
			if (DEBUG) {
				throw $e;
			}

			Application::$response->setStatusCode(500);
		}

		return null;
	}
}
