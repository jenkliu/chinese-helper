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

	// using direct query
	$sql = "SELECT $chartype, pinyin, english from entry
				WHERE $chartype LIKE '$term%'
				ORDER BY LENGTH($chartype) ASC";

	$first_result = $dbh->query($sql);

	$sql = "SELECT $chartype, pinyin, english from entry
				WHERE $chartype LIKE '_%$term%'
				ORDER BY LENGTH($chartype) ASC";

	$contains_result = $dbh->query($sql);

?>



<?php if($exact) : ?>
	<div class="well">
			<div class="primary result">
				<div class="row">
					<div class="span2">
						<span class="char main"><?php echo $term; ?></span>
					</div>
					<div class="span9">
						<?php foreach(($exact) as $row) : ?>
							<span class="pinyin"><?php echo pinyin_addaccents($row['pinyin']); ?></span>
							- <span class="english"><?php echo $row['english']; ?></span><br/>
						<?php endforeach ?>
					</div>
				</div>
			</div>
	</div>
<?php endif ?>


<ul class="nav nav-tabs">
	<li><a href="#first-char" data-toggle="tab">first</a></li>
	<li><a href="#contains-char" data-toggle="tab">contains</a></li>
	<li><a href="#all" data-toggle="tab">all</a></li>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="first-char">
		<?php if($first_result) : ?>
			<table class="terms">
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
			</table>
		<?php endif ?>
	</div>
	<div class="tab-pane" id="contains-char">
		<?php if($contains_result) : ?>
			<table class="terms table">
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
			</table>
		<?php endif ?>
	</div>
	<div class="tab-pane" id="all">
	</div>
</div>