<?php

declare(strict_types=1);

namespace App\config;

return [
	'db' => [
		'dsn'      => 'mysql:host=todo_mvc_db;port=33069;dbname=todo_mvc',
		'username' => 'root',
		'password' => 'root',
	],
	'auth' => [
		'site_key'      => 'todo_mvc_auth',
		'site_timezone' => 'Asia/Vladivostok',
		'cookie_name'   => 'todo_mvc_auth',

		'table_attempts'      => 'phpauth_attempts',
		'table_requests'      => 'phpauth_requests',
		'table_sessions'      => 'phpauth_sessions',
		'table_users'         => 'phpauth_users',
		'table_emails_banned' => 'phpauth_emails_banned',
	],
];
