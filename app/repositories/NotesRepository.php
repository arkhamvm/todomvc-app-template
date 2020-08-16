<?php

declare(strict_types=1);

namespace App\repositories;

use App\base\Application;
use App\dtos\NoteDTO;
use PDO;

/**
 * Репозиторий заметок.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class NotesRepository {

	/**
	 * Поиск всех заметок по пользователю.
	 *
	 * @param int $userId Идентификатор пользователя.
	 *
	 * @return NoteDTO[] Массив заметок
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function getByUserId(int $userId): array {
		$stmt = Application::$db->prepare(
<<<SQL
		SELECT id, user_id, description, is_completed, is_deleted, insert_stamp, update_stamp
		FROM ref_notes
		WHERE user_id=:user_id
			AND is_deleted = false
		ORDER BY insert_stamp ASC
SQL
		);

		$stmt->bindParam(':user_id', $userId);
		$stmt->execute();

		$stmt->setFetchMode(PDO::FETCH_CLASS, NoteDTO::class);

		$notes = [];
		while ($note = $stmt->fetchObject(NoteDTO::class)) {
			$notes[] = $note;
		}

		return $notes;
	}

	/**
	 * Вставка заметки.
	 *
	 * @param NoteDTO $dto Объект заметки
	 *
	 * @return bool Результат операции
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function insert(NoteDTO $dto): bool {
		$stmt = Application::$db->prepare(
<<<SQL
		INSERT INTO ref_notes (user_id, description)
		VALUES (:user_id, :description)
SQL
		);

		$stmt->bindParam(':user_id', $dto->user_id);
		$stmt->bindParam(':description', $dto->description);

		return $stmt->execute();
	}

	/**
	 * Обновление текста заметки.
	 *
	 * @param NoteDTO $dto Объект заметки
	 *
	 * @return bool Результат операции
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function updateDescription(NoteDTO $dto): bool {
		$stmt = Application::$db->prepare(
<<<SQL
		UPDATE ref_notes
		SET description=:description
		WHERE id = :id
SQL
		);

		$stmt->bindParam(':id', $dto->id);
		$stmt->bindParam(':description', $dto->description);

		return $stmt->execute();
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
		$stmt = Application::$db->prepare(
<<<SQL
		UPDATE ref_notes
		SET is_completed = NOT is_completed
		WHERE id IN (:ids) AND user_id = :user_id
SQL
		);

		$stmt->bindParam(':ids', $ids);
		$stmt->bindParam(':user_id', $userId);

		return $stmt->execute();
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
		$stmt = Application::$db->prepare(
<<<SQL
		UPDATE ref_notes
		SET is_deleted = TRUE
		WHERE id = :id
SQL
		);

		$stmt->bindParam(':id', $id);

		return $stmt->execute();
	}
}
