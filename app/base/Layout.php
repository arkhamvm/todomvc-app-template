<?php

declare(strict_types=1);

namespace App\base;

/**
 * Базовый класс для шаблонов.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
abstract class Layout extends View {
	/** @var string Контент внутри шаблона */
	protected string $viewContent;

	/**
	 * Получение функции для отрисовки контента.
	 *
	 * @param string $viewContent Внутренний контент для отрисовки
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function __construct(string $viewContent = '') {
		$this->viewContent = $viewContent;
	}
}
