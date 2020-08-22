<?php

declare(strict_types=1);

namespace App\views\layouts;

use App\base\Layout;

/**
 * Шаблон по умолчанию.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class DefaultLayout extends Layout {
	/**
	 * {@inheritdoc}
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function getRenderer(): callable {
		$viewContent = $this->viewContent;

		return function() use ($viewContent) {
?>
<!doctype html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Template • TodoMVC</title>
		<link rel="stylesheet" href="/static/todomvc-common/base.css">
		<link rel="stylesheet" href="/static/todomvc-app-css/index.css">
		<!-- CSS overrides - remove if you don't need it -->
		<link rel="stylesheet" href="/static/css/app.css">
	</head>
	<body>
        <?= $viewContent ?>
		<script src="/static/todomvc-common/base.js"></script>
		<script src="/static/js/app.js"></script>
	</body>
</html>
<?php };}}
