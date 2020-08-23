<?php

declare(strict_types=1);

namespace App\views\pages;

use App\base\View;

/**
 * Шаблон страницы заметок.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class NotesPage extends View {
	/**
	 * {@inheritdoc}
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function getRenderer(): callable {
		return function() {
?>
<section class="todoapp">
	<header class="header">
		<h1>todos</h1>
		<input class="new-todo" placeholder="What needs to be done?" autofocus>
	</header>

	<section class="main">
		<input id="toggle-all" class="toggle-all" type="checkbox">
		<label for="toggle-all">Mark all as complete</label>
		<ul class="todo-list"></ul>
	</section>
	<footer class="footer">
		<span class="todo-count"><strong>0</strong> item(s) left</span>
		<ul class="filters">
			<li>
				<a data-filter="all" href="#/">All</a>
			</li>
			<li>
				<a data-filter="active" href="#/active">Active</a>
			</li>
			<li>
				<a data-filter="completed" href="#/completed">Completed</a>
			</li>
		</ul>
		<button class="clear-completed">Clear completed</button>
	</footer>
</section>
<footer class="info">
	<p>Double-click to edit a todo</p>
</footer>
<script src="/static/js/jquery-3.5.1.min.js"></script>
<script src="/static/js/notes.js"></script>
<style type="text/css">
	.todo-list {
		transition: all 0.25s;
	}
</style>
<?php };}}
