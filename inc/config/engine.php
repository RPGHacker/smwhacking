<?php

	define('DEFAULT_PAGE_NAME', 'home');

	define('DEFAULT_SUBPAGE_NAME', '');

	define('MENU_ITEMS', [
		[
			'page'    => 'home',
			'caption' => CAPTION_HOME,
		], [
			'page'    => 'about',
			'caption' => CAPTION_ABOUT,
		], [
			'page'         => 'forums',
			'caption'      => CAPTION_FORUM,
			'relatedPages' => ['forum', 'thread', 'new-post', 'edit-post', 'delete-post', 'moderate-thread',
				'admin', 'award-automatic-medals', 'award-medal', 'ban', 'medals', 'move-thread', 'new-pm', 'new-post',
				'new-thread', 'pm', 'posts', 'watch-thread'
			],
		], [
			'page'         => 'users',
			'caption'      => CAPTION_USERS,
			'relatedPages' => ['user', 'edit-profile'],
		], [
			'page'    => 'chat',
			'caption' => CAPTION_CHAT,
		], [
			'link'    => 'https://discord.gg/yFHEmTx',
			'caption' => CAPTION_DISCORD,
		], [
			'page'         => 'files',
			'caption'      => CAPTION_FILES,
			'relatedPages' => ['upload'],
		], [
			'page'    => 'secret',
			'caption' => CAPTION_SECRET,
			'secret'  => true
		],
	]);