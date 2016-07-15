<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';
	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/medals.php';
	require_once __DIR__ . '/../functions/avatar.php';


	do
	{
		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		}
		$userId = (int)$_GET['id'];

		$isOwnProfile = isLoggedIn() && $userId === (int)$_SESSION['userId'];

		$user = getUser($userId);

		if ($user === null)
		{
			renderErrorMessage(MSG_USER_DOESNT_EXIST);
			break;
		}

		$lastPost = getLastPostByUser($userId);
		$lastPostPage = ($lastPost !== null) ? getPostPageInThread($lastPost['id'], $lastPost['thread_id']) : '';
		$canViewLastPost = canView($lastPost['min_powerlevel']);

		renderTemplate('user_top', [
			'canEditProfile' => !isBanned() && ($isOwnProfile || isAdmin()),
			'canBan'         => !$isOwnProfile && isModerator(),
			'canGiveMedal'   => isModerator(),
			'name'           => $user['name'],
			'id'             => $userId,
			'banned'         => $user['banned'],
			'token'          => getCsrfToken()
		]);

		renderTemplate('user_info', [
			'id'               => $userId,
			'powerlevel'       => POWERLEVEL_DESCRIPTIONS[$user['powerlevel']],
			'banned'           => $user['banned'],
			'rank'             => getRank($userId),
			'title'            => $user['title'],
			'hasAvatar'        => hasAvatar($userId),
			'registrationTime' => date(DEFAULT_DATE_FORMAT, $user['registration_time']),
			'lastLoginTime'    => date(DEFAULT_DATE_FORMAT, $user['last_login_time']),
			'numPosts'         => getNumPostsByUser($userId),
			'canViewLastPost'  => $canViewLastPost,
			'lastPost'         => $lastPost,
			'lastPostPage'     => $lastPostPage,
			'website'          => $user['website'],
			'location'         => $user['location'],
			'emailHtml'        => obfuscateEmail($user['email'])
		]);

		renderTemplate('user_bio', [
			'bio'       => nl2br($user['bio']),
			'signature' => nl2br($user['signature'])
		]);

		$medals = getAwardedMedalsByUser($userId);
		$numTotalMedals = count($medals);

		$medalsByCategory = getMedalsByCategory($medals);

		renderTemplate('user_medals', [
			'numTotalMedals'   => $numTotalMedals,
			'medalsByCategory' => $medalsByCategory,
		]);
	}
	while (false);

