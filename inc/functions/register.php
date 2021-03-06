<?php

	require_once __DIR__ . '/../config/database.php';
	require_once __DIR__ . '/../config/notifications.php';
	require_once __DIR__ . '/../config/user.php';
	require_once __DIR__ . '/../config/misc.php';


	function validateRegistrationForm()
	{
		$errorMessages = [];

		if (trim(getFieldValue('email')) === '')
		{
			$errorMessages[] = MSG_EMAIL_MISSING;
		}
		if (getFieldValue('email') !== getFieldValue('email-confirm'))
		{
			$errorMessages[] = MSG_EMAILS_DONT_MATCH;
		}
		if (!preg_match('/^' . VALID_USERNAME_REGEX . '$/', getFieldValue('username')))
		{
			$errorMessages[] = MSG_INVALID_USERNAME;
		}
		if (strlen(getFieldValue('password')) < 8)
		{
			$errorMessages[] = MSG_PASSWORD_TOO_SHORT;
		}
		if (strtolower(getFieldValue('password')) === 'penis')
		{
			$errorMessages[] = MSG_PASSWORD_PENIS;
		}
		if (getFieldValue('password') !== getFieldValue('password-confirm'))
		{
			$errorMessages[] = MSG_PASSWORDS_DONT_MATCH;
		}
		if (str_ireplace(' ', '', strtolower(getFieldValue('security-answer'))) !== SECURITY_ANSWER)
		{
			$errorMessages[] = MSG_WRONG_SECURITY_ANSWER;
		}
		if (emailExists(getFieldValue('email')))
		{
			$errorMessages[] = MSG_EMAIL_TAKEN;
		}
		if (usernameExists(getFieldValue('username')))
		{
			$errorMessages[] = MSG_USERNAME_TAKEN;
		}

		return $errorMessages;
	}


	function emailExists($email)
	{
		global $database;

		$numEmails = $database->count('users', [
			'email' => $email
		]);

		return (is_int($numEmails) && $numEmails > 0);
	}


	function usernameExists($username)
	{
		global $database;

		$numUsers = $database->count('users', [
			'name' => $username
		]);

		return (is_int($numUsers) && $numUsers > 0);
	}


	function startRegistration($email, $username, $passwordHash)
	{
		global $database;

		$activationToken = bin2hex(random_bytes(16));

		$userId = $database->insert('users', [
			'id'                   => null,
			'email'                => strtolower(htmlspecialchars($email)),
			'name'                 => htmlspecialchars($username),
			'password'             => $passwordHash,
			'legacy_login'         => 0,
			'registration_time'    => time(),
			'activated'            => 0,
			'enable_notifications' => 1,
			'activation_token'     => $activationToken
		]);

		ob_start();
		renderTemplate('registration_email', [
			'userId'          => $userId,
			'activationToken' => $activationToken
		]);
		$messageBody = ob_get_clean();

		if (isLocalEnv())
		{
			echo $messageBody;
		}

		$headers = 'From: ' . NOTIFICATION_SENDER_ADDRESS . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";

		mail($email, MSG_REGISTRATION_EMAIL_SUBJECT, $messageBody, $headers);
	}
	
	