<?php if ($numFavoriteMedals !== 0): ?>
	<section class="user-section">
		<h3>Lieblings-Medaillen (<?= $numFavoriteMedals ?>)</h3>
		<div class="content medals">

			<?php foreach ($favoriteMedals as $medal): ?>
				<div class="medal">
					<img src="img/medals/<?= $medal['image_filename'] ?>" alt="<?= $medal['name'] ?>" />
					<div>
						<h5><?= $medal['name'] ?></h5>
						<?php if ($medal['visible']): ?>
							<p><?= $medal['description'] ?></p>
						<?php else: ?>
							<p><?= MSG_SECRET_MEDAL_DESCRIPTION ?></p>
						<?php endif; ?>
						<p>verliehen am <?= date(DEFAULT_DATE_FORMAT, $medal['award_time']) ?></p>
					</div>
				</div>
			<?php endforeach; ?>

		</div>
	</section>
<?php endif; ?>


<section class="user-section">
	<?php if ($numTotalMedals !== 0 && $numFavoriteMedals !== 0): ?>
		<h3>Alle Medaillen (<?= $numTotalMedals ?>)</h3>
	<?php elseif ($numTotalMedals !== 0): ?>
		<h3>Medaillen (<?= $numTotalMedals ?>)</h3>
	<?php else: ?>
		<h3>Medaillen</h3>
	<?php endif ?>

	<div class="content medals">

		<?php if ($numTotalMedals === 0): ?>
			<em><?= MSG_USER_NO_MEDALS ?></em>
		<?php endif; ?>

		<?php foreach ($medalsByCategory as $category => $medals): ?>

			<h4><?= $medals[0]['category_name'] ?> (<?= count($medals) ?>)</h4>

			<?php foreach ($medals as $medal): ?>
				<div class="medal">
					<img src="img/medals/<?= $medal['image_filename'] ?>" alt="<?= $medal['name'] ?>" />
					<div>
						<h5><?= $medal['name'] ?></h5>
						<?php if ($medal['visible']): ?>
							<p><?= $medal['description'] ?></p>
						<?php else: ?>
							<p><?= MSG_SECRET_MEDAL_DESCRIPTION ?></p>
						<?php endif; ?>
						<p>verliehen am <?= date(DEFAULT_DATE_FORMAT, $medal['award_time']) ?></p>
					</div>
				</div>
			<?php endforeach; ?>

		<?php endforeach; ?>

		<br /><br /><a class="subtle button" href="?p=medals">Alle verleihbaren Medaillen ansehen</a>

	</div>
</section>