<?php

	require_once __DIR__ . '/../config/forums.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/session.php';


	function getPostById($postId)
	{
		global $database;

		$post = $database->get('posts', [
			'[>]threads' => ['thread' => 'id'],
			'[>]users'   => ['author' => 'id']
		], [
			'threads.id(thread_id)',
			'users.id(author_id)',
			'users.name(author_name)',
			'posts.id(id)',
			'posts.content',
			'posts.post_time'
		], [
			'posts.id' => $postId
		]);

		if (!is_array($post) || empty($post))
		{
			return null;
		}

		return $post;
	}


	function getPostsInThread($threadId, $page)
	{
		global $database;

		$posts = $database->select('posts', [
			'[>]users' => ['author' => 'id'],
		], [
			'posts.id',
			'posts.post_time',
			'posts.content',
			'users.id(author_id)',
			'users.name(author_name)',
			'users.powerlevel(author_powerlevel)',
			'users.title(author_title)',
			'users.signature(author_signature)',
			'users.registration_time(author_registration_time)',
			'users.banned(author_banned)'
		], [
			'AND'   => [
				'thread'  => $threadId,
				'deleted' => 0
			],
			'ORDER' => 'post_time ASC',
			'LIMIT' => [($page - 1) * POSTS_PER_PAGE, POSTS_PER_PAGE]
		]);

		if ($posts === false)
		{
			return [];
		}

		return $posts;
	}


	function getPostPageInThread($postId, $threadId)
	{
		global $database;

		$numPostsUpTo = $database->count('posts', [
			'AND' => [
				'thread'  => $threadId,
				'id[<=]'  => $postId,
				'deleted' => 0
			]
		]);

		return ceil($numPostsUpTo / POSTS_PER_PAGE);
	}


	function getLastEdit($postId)
	{
		global $database;

		$edits = $database->select('edits', [
			'[>]users' => ['user' => 'id']
		], [
			'edits.edit_time',
			'users.id(editor_id)',
			'users.name(editor_name)'
		], [
			'post'  => $postId,
			'ORDER' => 'edit_time DESC',
			'LIMIT' => 1
		]);

		if (count($edits) !== 1)
		{
			return null;
		}

		return $edits[0];
	}


	function createPost($threadId, $postText, $postTime = null)
	{
		global $database;

		if (!isLoggedIn())
		{
			return null;
		}

		$postTime = $postTime ?: time();

		$newPostId = $database->insert('posts', [
			'id'        => null,
			'thread'    => $threadId,
			'author'    => $_SESSION['userId'],
			'post_time' => $postTime,
			'content'   => htmlspecialchars($postText),
			'deleted'   => 0
		]);
		$database->update('threads', [
			'posts[+]'       => 1,
			'last_post'      => $newPostId,
			'last_post_time' => $postTime
		], [
			'id' => $threadId
		]);

		$forumId = getForumIdByThreadId($threadId);

		$database->update('forums', [
			'posts[+]'  => 1,
			'last_post' => $newPostId
		], [
			'id' => $forumId
		]);

		return $newPostId;
	}


	function editPost($postId, $postText)
	{
		global $database;

		if (!isLoggedIn())
		{
			return null;
		}

		$newPostId = $database->update('posts', [
			'content' => htmlspecialchars($postText),
		], [
			'id' => $postId
		]);

		$database->insert('edits', [
			'post'      => $postId,
			'user'      => $_SESSION['userId'],
			'edit_time' => time()
		]);

		return $newPostId;
	}


	function isFirstPostOfThread($postId, $threadId)
	{
		global $database;

		$id = $database->get('posts', 'id', [
			'AND'   => [
				'thread'  => $threadId,
				'deleted' => 0
			],
			'ORDER' => 'id ASC',
			'LIMIT' => 1
		]);

		return $id[0] === $postId;
	}


	function isLastPostOfThread($postId, $threadId)
	{
		global $database;

		$id = $database->get('posts', 'id', [
			'AND'   => [
				'thread'  => $threadId,
				'deleted' => 0
			],
			'ORDER' => 'id DESC',
			'LIMIT' => 1
		]);

		return $id[0] === $postId;
	}


	function isPostDeleted($postId)
	{
		global $database;

		return $database->count('posts', [
			'AND' => [
				'id'      => $postId,
				'deleted' => 1
			]
		]) === 1;
	}


	function deletePost($postId, $threadId)
	{
		global $database;

		if (isPostDeleted($postId))
		{
			return;
		}

		$wasLastPost = isLastPostOfThread($postId, $threadId);

		$database->update('posts', [
			'deleted' => 1
		], [
			'id' => $postId,
		]);

		$database->update('threads', [
			'posts[-]' => 1
		], [
			'id' => $threadId,
		]);

		$forumId = getForumIdByThreadId($threadId);

		$database->update('forums', [
			'posts[-]' => 1
		], [
			'id' => $forumId,
		]);

		if ($wasLastPost)
		{
			updateLastPostInThread($threadId);
		}
	}