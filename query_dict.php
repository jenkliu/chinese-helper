<?php
	include 'config_db.php';
	include 'helpers.php';

	//debugger
	include 'ChromePhp.php';

	header('Content-type: text/html; charset=utf-8');

	$term = $_GET['term'];
	$chartype = $_GET['chartype'];
	ChromePhp::log($chartype);
	ChromePhp::log($term);
	$dbh->query("SET NAMES utf8");

	$sql = "SELECT *
				FROM `entry`
				WHERE `traditional` = '$term'
				OR `simplified` = '$term'";

	$exact = $dbh->query($sql);

	$exact_count = $dbh->query("SELECT COUNT(*)
								FROM `entry`
								WHERE `traditional` = '$term'
								OR `simplified` = '$term'")->fetchColumn();

	$as_first = $term.'_%';

	$sql = "SELECT $chartype, pinyin, english from entry
				WHERE $chartype LIKE '$as_first'
				ORDER BY LENGTH($chartype) ASC";

	$first_result = $dbh->query($sql);

	$sql = "SELECT $chartype, pinyin, english from entry
				WHERE $chartype LIKE '_%$term%'
				ORDER BY LENGTH($chartype) ASC";

	$contains_result = $dbh->query($sql);

?>


	<table class="terms">

		<?php if($exact_count > 0) : ?>
			<tr class="primary result">
				<?php $row = $exact->fetch(); ?>
				<td rowspan="<?php echo $exact_count ?>">
					<span class="char main"><?php echo $row[$chartype]; ?></span>
				</td>
				<td>
					<span class="pinyin"><?php echo pinyin_addaccents($row['pinyin']); ?></span>
				</td>
				<td>
					<span class="english"><?php echo $row['english']; ?></span><br/>
				</td>
			</tr>
			<?php while ($row = $exact->fetch()) : ?>
				<tr class="primary result">
					<td>
						<span class="pinyin"><?php echo pinyin_addaccents($row['pinyin']); ?></span>
					</td>
					<td>
						<span class="english"><?php echo $row['english']; ?></span><br/>
					</td>
				</tr>
			<?php endwhile ?>
		<?php endif ?>


		<?php if($first_result) : ?>
				<?php foreach(($first_result) as $row) : ?>
					<tr>
						<td>
							<span class="char"><?php echo highlight($row[0], $term); ?></span>
						</td>
						<td>
							<span class="pinyin">
								<?php echo pinyin_addaccents($row['pinyin']); ?>
							</span>
						</td>
						<td><?php echo $row['english']; ?></td>
					</tr>
				<?php endforeach ?>
		<?php endif ?>
		<?php if($contains_result) : ?>
				<?php foreach(($contains_result) as $row) : ?>
					<tr>
						<td>
							<span class="char"><?php echo highlight($row[0], $term); ?></span>
						</td>
						<td>
							<span class="pinyin">
								<?php echo pinyin_addaccents($row['pinyin']); ?>
							</span>
						</td>
						<td><?php echo $row['english']; ?></td>
					</tr>
				<?php endforeach ?>
		<?php endif ?>
	</table>

	</div>
	<div class="tab-pane" id="all">
	</div>
</div>