<?php

declare(strict_types=1);

namespace App\views\pages;

use App\base\Application;
use App\base\View;

/**
 * Шаблон страницы авторизации.
 *
 * @author Vladimir <arkham.vm@gmail.com>
 */
class AuthPage extends View {
	protected ?string $error;

	public function __construct(?string $error = null) {
		$this->error = $error;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @author Vladimir <arkham.vm@gmail.com>
	 */
	public function getRenderer(): callable {
		$user  = Application::$auth->getCurrentUser();
		$error = $this->error;

		return function() use ($user, $error) {
?>
<section class="todoapp">
	<header class="header">
		<h1>login/registration</h1>
	</header>
	<section class="main">
		<?php if ($error): ?>
			<div class="error">
				<?= $error ?>
			</div>
		<?php endif; ?>

		<?php if (false === $user): ?>
			<div class="tabs">
				<div class="login-tab">Login</div>
				<div class="register-tab">Register</div>
			</div>

			<form action="/auth/login" method="post" class="login-form">
				<div>
					<input type="email" name="email" placeholder="E-Mail" required >
				</div>
				<div>
					<input type="password" name="password" placeholder="Password" required>
				</div>

				<input class="submit" type="submit" value="Login">
			</form>

			<form action="/auth/register" method="post" class="register-form">
				<div>
					<input type="email" name="email" placeholder="E-Mail" required >
				</div>
				<div>
					<input type="password" name="password" placeholder="Password (3 symbols at least)" required>
				</div>
				<div>
					<input type="password" name="password_repeat" placeholder="Repeat password" required>
				</div>

				<input class="submit" type="submit" value="Register">
			</form>
		<?php else: ?>
			<form action="/auth/logout" method="post" class="logout-form">
				<h3>Loginned as:</h3>
				<h2><?= $user['email'] ?></h2>

				<input class="submit" type="submit" value="Logout">
			</form>
		<?php endif; ?>
	</section>
	<script src="/static/js/jquery-3.5.1.min.js"></script>
	<script type="application/javascript" defer>
		const loginForm    = $('.login-form');
		const registerForm = $('.register-form');

		$('.register-tab').each(function(){
			$(this).click(function(){
				loginForm.hide();
				registerForm.show();
			});
		});

		$('.login-tab').each(function(){
			$(this).click(function(){
				loginForm.show();
				registerForm.hide();
			});
		});
	</script>
	<style type="text/css">
		.error {
			padding:          2em;
			color:            #af5b5e;
			background-color: whitesmoke;
		}

		.tabs {
			display:         flex;
			justify-content: space-between;
		}

		.login-tab, .register-tab {
			color:          whitesmoke;
			font-weight:    bold;
			width:          50%;
			text-align:     center;
			vertical-align: middle;
			line-height:    40px;
			cursor:         pointer;
		}

		.login-tab, .login-form, .logout-form {
			background-color: lightsteelblue;
		}

		.register-tab, .register-form {
			background-color: lightslategrey;
		}

		.login-form, .logout-form, .register-form {
			padding: 2em;
		}

		.login-form input, .logout-form input, .register-form input {
			padding: 0.6em;
			margin:  1em;
			width:   300px;
		}

		.register-form {
			display: none;
		}

		.submit {
			margin-top:       2em;
			border:           none;
			background-color: white;
			font-size:        15px;
			font-weight:      bold;
			transition:       all 0.5s;
		}

		.submit:hover {
			color: white;
		}

		.login-form .submit:hover, .logout-form .submit:hover {
			background-color: lightslategrey;
		}

		.register-form .submit:hover {
			background-color: lightsteelblue;
		}
	</style>
</section>
<?php };}}
