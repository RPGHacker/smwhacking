<h2>Dateiablage</h2>

<p>Hier können alle möglichen Dateien zwischengelagert werden, die sonst nirgends hingehören (außer ROMs natürlich, und
	allem, was sonst irgendwie unerwünscht ist). Sie müssen nicht mal mit Hacking zu tun haben.</p>

<table class="files">
	<thead>
	<tr>
		<th class="name">Name</th>
		<th class="description">Beschreibung</th>
		<th class="upload-date">hochgeladen am</th>
		<th class="uploader">hochgeladen von</th>
		<th class="actions">Aktionen</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($files as $file): ?>
		<tr>
			<td class="name"><?= $file['name'] ?></td>
			<td class="description">
				<?= $file['short_description'] ?>
				<?php if (trim($file['long_description']) !== ''): ?>
					<a class="small subtle button description-toggle">mehr&hellip;</a>
					<p class="long-description"><?= $file['long_description'] ?></p>
				<?php endif ?>
			</td>
			<td class="upload-date"><?= date(DEFAULT_DATE_FORMAT, $file['upload_time']) ?></td>
			<td class="uploader"><a href="?p=user&id=<?= $file['user_id'] ?>"><?= $file['username'] ?></a></td>
			<td class="actions">
				<a class="small button" href="download.php?id=<?= $file['id'] ?>">Download</a>
				<?php if ($file['can_delete']): ?>
					<a class="small subtle button">Löschen</a>
				<?php endif ?>
			</td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table>

<script type="text/javascript">

	var toggleButtons = document.getElementsByClassName('description-toggle');
	for (var i = 0; i < toggleButtons.length; i++) {
		(function (i) {
			toggleButtons[i].onclick = function () {
				var toggleButton = toggleButtons[i];
				var longDescriptionBox = toggleButton.parentNode.getElementsByTagName('p')[0];
				if (window.getComputedStyle(longDescriptionBox).getPropertyValue('display') === 'none') {
					longDescriptionBox.style.display = 'block';
					toggleButton.innerHTML = 'weniger&hellip;';
				} else {
					longDescriptionBox.style.display = 'none';
					toggleButton.innerHTML = 'mehr&hellip;';
				}
			};
		})(i);
	}

</script>