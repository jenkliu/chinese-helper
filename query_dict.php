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


// **using prepare statements and variables
// 	$sql = "SELECT *
// 	 			FROM  `entry`
// 	 			WHERE  `traditional` LIKE  '%$term%'
// 	 			OR `simplified` LIKE '%$term%'
// 	 			OR `pinyin` LIKE '$term'";

// 	ChromePhp::log('Hello？');
// try {
// 	$stmt = $dbh->prepare($sql);
// 	$params = array("%$term%", "%$term%", "$term");
// 	$stmt->execute($params);
// 	ChromePhp::log('again');

// 	$result = $stmt->$fetchAll();
// 	print_r($result);
// } catch(PDOException $e){
//     echo $this->pack('dbError', $e->getMessage());
//     ChromePhp::log($this->pack('dbError', $e->getMessage()));
// }

	$sql = "SELECT *
				FROM `entry`
				WHERE `traditional` = '$term'
				OR `simplified` = '$term'";

	$exact = $dbh->query($sql)->fetch();

	// using direct query
	// consider doing separate queries for positioning, each ordered by length
	$sql = "SELECT $chartype, pinyin, english, 1 as rank from entry
				WHERE $chartype LIKE '$term%'
			UNION
			SELECT $chartype, pinyin, english, 2 as rank from entry
				WHERE $chartype LIKE '_$term%'
			UNION
			SELECT $chartype, pinyin, english, 3 as rank from entry
				WHERE $chartype LIKE '__$term%'";


	$contains_result = $dbh->query($sql);


?>
<?php if($exact) : ?>
	<div class="well">
		<div class="primary result">
			<?php echo $exact[0]; ?>
		</div>
		<br /><?php echo pinyin_addaccents($exact['pinyin']); ?>
		<br /><?php echo $exact['english']; ?>
	</div>
<?php endif ?>


<?php if($contains_result) : ?>
	<table class="table">
		<?php foreach(($contains_result) as $row) : ?>
			<tr>
				<td>
					<?php echo highlight($row[0], $term); ?>
					<br/>
					<span class="pinyin">
						<?php echo pinyin_addaccents($row['pinyin']); ?>
					</span>
				</td>
				<td><?php echo $row['english']; ?></td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>
