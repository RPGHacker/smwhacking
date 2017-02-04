<h2>Chat</h2>

<div class="chat">

	<div class="chat-header">
		<div class="grid">
			<div class="column">
				<button id="refresh" class="small primary"><i id="refresh-icon" class="fa fa-refresh fa-fw"></i>
					Aktualisieren
				</button>
				<span>zuletzt aktualisiert am <span id="refresh-date"><?= date(DEFAULT_DATE_FORMAT) ?></span></span>
			</div>
			<div class="column">
				<a class="small subtle button" href="#"><i class="fa fa-list"></i> Chat-Archiv</a>
			</div>
		</div>
	</div>

	<div id="chat-container" class="chat-messages">

		<?php foreach ($messages as $message): ?>

			<div class="chat-message" id="message-<?= $message['id'] ?>" data-id="<?= $message['id'] ?>">
				<div class="chat-sidebar">
					<?php if ($message['has_avatar']): ?>
						<img class="avatar" src="img/avatars/<?= $message['author_id'] ?>.png" />
					<?php else: ?>
						<img class="avatar" src="img/avatars/default.png" />
					<?php endif; ?>
				</div>
				<div class="chat-topbar">
					<a href="?p=user&id=<?= $message['author_id'] ?>"
					   class="username"><?= $message['author_name'] ?></a>
					<span><?= $message['post_time'] ?> (<a class="delete" href="#"
														   title="löschen"><i class="fa fa-trash"></i></a>)</span>
				</div>
				<div class="chat-message-content">
					<?= $message['content'] ?>
				</div>
				<div class="clearfix"></div>
			</div>

		<?php endforeach; ?>

	</div>

	<?php if ($loggedIn): ?>

		<div class="chat-footer">
			<div class="message-form">
				<textarea id="message-content" name="message"></textarea>
				<button id="send" class="primary" disabled="disabled"><i class="fa fa-send"></i> Senden
					<small>(<kbd>Strg</kbd>+<kbd>Enter</kbd>)</small>
				</button>
			</div>
		</div>

	<?php endif; ?>

</div>

<script type="text/javascript" src="js/cuteedit.js"></script>
<script type="text/javascript" src="js/smiley_editor.js.php"></script>
<script type="text/javascript" src="js/nano.js"></script>
<script type="text/javascript" src="js/nanoajax.min.js"></script>
<script type="text/javascript" src="js/chat.js.php"></script>
<script type="text/javascript">

    var editor = new CuteEdit('message-content');

</script>