<?php

declare(strict_types=1);

namespace App\services;

use App\base\Application;
use App\dtos\NoteDTO;
use App\repositories\NotesRepository;
use Exception;

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

		$dto->user_id     = Application::$auth->getCurrentUID();
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
	public function updateDescription(int $id, string $description): bool {
		$dto = new NoteDTO();

		$dto->id          = $id;
		$dto->description = $description;

		return $this->repository->updateDescription($dto);
	}

	/**
	 * Смена флага завершённости заметок.
	 *
	 * @param int   $userId Идентификатор пользователя
	 * @param int[] $ids    Идентификаторы заметок
	 *
	 * @return bool Результат операции
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function toggleCompleted(int $userId, array $ids): bool {
		return $this->repository->toggleCompleted($userId, $ids);
	}

	/**
	 * Удаление заметки.
	 *
	 * @param int $id Идентификатор заметки
	 *
	 * @return bool Результат операции
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function delete(int $id): bool {
		return $this->repository->delete($id);
	}
}
