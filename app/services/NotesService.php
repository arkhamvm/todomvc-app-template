<?php

declare(strict_types=1);

namespace App\services;

use App\base\Application;
use App\dtos\NoteDTO;
use App\repositories\NotesRepository;

/**
 * Сервис заметок
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class NotesService {

	/** Репозиторий заметок */
	protected ?NotesRepository $repository = null;

	public function __construct() {
		$this->repository = new NotesRepository();
	}

	/**
	 * Поиск всех заметок пользователя.
	 *
	 * @return NoteDTO[] Массив заметок
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function getByUser(): array {
		$userId = (int)Application::$auth->getCurrentUID();

		return $this->repository->getByUserId($userId);
	}

	/**
	 * Обновление текста заметки.
	 *
	 * @param string $description Текст заметки
	 *
	 * @return bool Результат операции
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function create(string $description): bool {
		$dto = new NoteDTO();

		$dto->user_id     = (int)Application::$auth->getCurrentUID();
		$dto->description = $description;

		return $this->repository->insert($dto);
	}

	/**
	 * Обновление текста заметки.
	 *
	 * @param int    $id          Идентификатор заметки
	 * @param string $description Текст заметки
	 *
	 * @return bool Результат операции
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function update(int $id, string $description): bool {
		$dto = new NoteDTO();

		$dto->id          = $id;
		$dto->user_id     = (int)Application::$auth->getCurrentUID();
		$dto->description = $description;

		return $this->repository->update($dto);
	}

	/**
	 * Смена флага завершённости заметок.
	 *
	 * @param int[] $ids Идентификаторы заметок
	 *
	 * @return bool Результат операции
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function toggleCompleted(array $ids): bool {
		$userId = (int)Application::$auth->getCurrentUID();

		return $this->repository->toggleCompleted($userId, $ids);
	}

	/**
	 * Удаление заметки.
	 *
	 * @param int[] $ids Идентификаторы заметок
	 *
	 * @return bool Результат операции
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function delete(array $ids): bool {
		$userId = (int)Application::$auth->getCurrentUID();

		return $this->repository->delete($userId, $ids);
	}
}
