<?php

declare(strict_types=1);

namespace App\dtos;

/**
 * DTO Заметки.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class NoteDTO {
	/** Идентификатор заметки */
	public ?int $id;
	/** Идентификатор пользователя */
	public ?int $user_id;
	/** Текст заметки */
	public ?string $description;
	/** Флаг завершённости */
	public ?bool $is_completed;
	/** Флаг удалённости */
	public ?bool $is_deleted;
	/** Таймстамп вставки */
	public ?int $insert_stamp;
	/** Таймстамп изменения */
	public ?int $update_stamp;
}
