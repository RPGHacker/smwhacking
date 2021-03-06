<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';
	require_once __DIR__ . '/../functions/pagination.php';
	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/medals.php';
	require_once __DIR__ . '/../functions/avatar.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (!isset($_GET['user']) || !is_int($_GET['user'] * 1))
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		}
		$userId = (int)$_GET['user'];

		$user = getUser($userId);

		if ($user === null)
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		}

		$numPosts = getNumPostsByUser($userId);

		renderTemplate('posts_top', [
			'userName' => $user['name'],
			'numPosts' => $numPosts
		]);

		$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;
		$numPages = (int)ceil($numPosts / POSTS_PER_PAGE);
		makeBetween($page, 1, $numPages);
		renderPagination('?p=posts&user=' . $userId, $page, $numPages);

		$posts = getPostsByUser($userId, $page);

		foreach ($posts as $post)
		{
			if (!canView($post['view_powerlevel']))
			{
				renderTemplate('forbidden_post', []);
				continue;
			}

			renderTemplate('post', [
				'inThread'      => false,
				'id'            => $post['id'],
				'threadId'      => $post['thread_id'],
				'threadName'    => $post['thread_name'],
				'postTime'      => date(DEFAULT_DATE_FORMAT, $post['post_time']),
				'content'       => parseBBCode($post['content']),
				'pageInThread'  => getPostPageInThread($post['id'], $post['thread_id']),
				'lastEdit'      => getLastEdit($post['id']),
				'canPost'       => isLoggedIn(),
				'canModifyPost' => false,
				'author'        => [
					'id'                => $userId,
					'name'              => $user['name'],
					'powerlevelId'      => (int)$user['powerlevel'],
					'powerlevel'        => POWERLEVEL_DESCRIPTIONS[$user['powerlevel']],
					'banned'            => $user['banned'],
					'title'             => $user['title'],
					'rank'              => getRank($user['id']),
					'hasAvatar'         => hasAvatar($user['id']),
					'registrationTime'  => date(DEFAULT_DATE_FORMAT, $user['registration_time']),
					'favoriteMedals'    => getFavoriteMedals($user['id']),
					'currentPostNumber' => getCurrentPostNumber($user['id'], $post['id']),
					'numTotalPosts'     => $numPosts,
					'signature'         => parseBBCode($user['signature'])
				]
			]);
		}

		renderPagination('?p=posts&user=' . $userId, $page, $numPages);

		renderTemplate('spoiler_js', []);
	}
	while (false);