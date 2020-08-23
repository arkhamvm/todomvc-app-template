/**
 * Скрипты приложения для страницы заметок
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
(function (window) {
	'use strict';

	const FILTER_ALL       = 'all';
	const FILTER_ACTIVE    = 'active';
	const FILTER_COMPLETED = 'completed';

	const NotesApp = {
		items:  [],
		filter: FILTER_ALL,
		init:   async function () {
			await this.initFilter();
			await this.bindEvents();
			await this.refresh();
		},
		bindEvents: async function () {
			$('.new-todo').on('keyup', this.create.bind(this));
			$('.toggle-all').on('change', this.toggleAll.bind(this));
			$('.footer').on('click', '.clear-completed', this.deleteCompleted.bind(this));
			$('.filters').on('click', 'a', this.changeFilter.bind(this))

			await this.bindListEvents()
		},
		bindListEvents: async function () { // При перерисовке нам нужно обновлять только события списка
			const list = $('.todo-list');

			list.on('click',  '.destroy', this.delete.bind(this));
			list.on('change', '.toggle',  this.toggle.bind(this));

			// Режим редактирования
			list.on('dblclick', 'label', this.editActive.bind(this));
			list.on('keyup',    '.edit', this.editKeyup.bind(this))
			list.on('focusout', '.edit', this.update.bind(this))
		},
		refresh: async function () {
			await this.get();
			await this.render();
		},
		initFilter: async function() {
			switch (window.location.hash) {
				case '#/' + FILTER_COMPLETED:
					this.filter = FILTER_COMPLETED;
					break;
				case '#/' + FILTER_ACTIVE:
					this.filter = FILTER_ACTIVE;
					break;
				default:
					this.filter = FILTER_ALL;
					break;
			}

			$('.filters a').each((idx, elem) => elem.classList.remove('selected'));
			$('.filters a[data-filter="' + this.filter + '"]:first').addClass('selected');
		},
		changeFilter: async function (event) {
			event.preventDefault();
			window.location.hash = '#/' + event.target.dataset['filter'];

			await this.initFilter();
			await this.render();
		},
		render: async function () {
			const oldList = $('.todo-list:first');
			const newList = $('<ul class="todo-list"></ul>')

			// Отрисовка списка
			this.items.forEach((item) => {
				switch (this.filter) {
					case FILTER_COMPLETED:
						if (true === item['is_completed']) {
							return;
						}
						break;
					case FILTER_ACTIVE:
						if (false === item['is_completed']) {
							return;
						}
						break;
					default:
						break;
				}

				const li = $('<li></li>');
				li.data('id', item['id']);
				li.appendTo(newList);

				const div = $('<div></div>').addClass('view');
				div.appendTo(li);

				const toggleInput = $('<input>').addClass('toggle').prop('type', 'checkbox')
				toggleInput.appendTo(div);

				const label = $('<label></label>').text(item['description']);
				label.appendTo(div);

				const button = $('<button></button>').addClass('destroy');
				button.appendTo(div);

				const editInput = $('<input>').addClass('edit').val(item['description'])
				editInput.appendTo(li);

				if (true === item['is_completed']) {
					li.addClass('completed')
					toggleInput.prop('checked', true);
				}
			})

			oldList.replaceWith(newList);

			// Отрисовка других элементов
			const active = this.getActiveNotes();
			$('.todo-count strong:first').text(active.length);
			$('.toggle-all').prop('checked', (0 === active.length));

			await this.bindListEvents();
		},
		get: async function () {
			const response = await fetch('/notes/get');

			const text = await response.text();
			if (response.ok) {
				this.items = JSON.parse(text)
			} else {
				alert('Ошибка: ' + text);
			}
		},
		create: async function (event) {
			if (13 !== event.which) { // Продолжаем только по нажатию на enter
				return;
			}

			const formData = new FormData();
			formData.append('description', event.target.value);
			const response = await fetch('/notes/create', {
				method: 'POST',
				body:   formData,
			});

			const text = await response.text();
			if (response.ok) {
				event.target.value = '';
				await this.refresh();
			} else {
				alert('Ошибка: ' + text);
			}
		},
		editActive: async function (event) {
			const li = $(event.target).closest('li');
			li.addClass('editing');
			li.find('.edit').focus();
		},
		editKeyup: async function (event) {
			if (13 === event.which) { // По нажатию на enter
				await this.update(event);
			}
			else if (27 === event.which) { // По нажатию на esc проставляем флаг отмены, т.к. подписаны мы на blur
				$(event.target).data('cancel', true).blur();
			}
		},
		update: async function (event) {
			const elem = $(event.target);
			if (elem.data('cancel')) {
				await this.refresh(); // Перерисовка что бы отменить изменения текста

				return;
			}

			const formData = new FormData();
			const li = $(event.target).closest('li');
			formData.append('id', li.data('id'));
			formData.append('description', event.target.value);
			const response = await fetch('/notes/update', {
				method: 'POST',
				body:   formData,
			});

			const text = await response.text();
			if (response.ok) {
				event.target.value = '';
				await this.refresh();
			} else {
				alert('Ошибка: ' + text);
			}
		},
		toggle: async function (event) {
			const li = $(event.target).closest('li');

			const formData = new FormData();
			formData.append('ids[]', li.data('id'));
			const response = await fetch('/notes/toggle', {
				method: 'POST',
				body:   formData,
			});

			const text = await response.text();
			if (response.ok) {
				await this.refresh();
			} else {
				alert('Ошибка: ' + text);
			}
		},
		toggleAll: async function () {
			if (0 === this.items.length) {
				return;
			}

			const active = this.getActiveNotes();

			// Если кол-во активных 0 либо равно все, шлём все. Если нет - то активные
			let items = active;
			if (0 === active.length || this.items.length === active.length) {
				items = this.items;
			}

			const formData = new FormData();
			items.forEach((item) => {
				formData.append('ids[]', item['id']);
			})

			const response = await fetch('/notes/toggle', {
				method: 'POST',
				body:   formData,
			});

			const text = await response.text();
			if (response.ok) {
				await this.refresh();
			} else {
				alert('Ошибка: ' + text);
			}
		},
		delete: async function (event) {
			const li = $(event.target).closest('li');

			const formData = new FormData();
			formData.append('ids[]', li.data('id'));
			const response = await fetch('/notes/delete', {
				method: 'POST',
				body:   formData,
			});

			const text = await response.text();
			if (response.ok) {
				await this.refresh();
			} else {
				alert('Ошибка: ' + text);
			}
		},
		deleteCompleted: async function () {
			const items = this.getCompletedNotes();
			if (0 === items.length) {
				return;
			}

			const formData = new FormData();
			items.forEach((item) => {
				formData.append('ids[]', item['id']);
			})

			const response = await fetch('/notes/delete', {
				method: 'POST',
				body:   formData,
			});

			const text = await response.text();
			if (response.ok) {
				await this.refresh();
			} else {
				alert('Ошибка: ' + text);
			}
		},
		getActiveNotes: function () {
			return this.items.filter((item) => {
				return (false === item['is_completed']);
			});
		},
		getCompletedNotes: function () {
			return this.items.filter((item) => {
				return (true === item['is_completed']);
			});
		},
	}

	NotesApp.init();
})(window);
