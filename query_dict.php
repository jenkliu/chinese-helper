<?php
	include 'config_db.php';
	include 'pinyin_accents.php';

	//debugger
	include 'ChromePhp.php';

	header('Content-type: text/html; charset=utf-8');

	$term = $_GET['term'];
	$chartype = $_GET['chartype'];

	$dbh->query("SET NAMES utf8");

	// $exact = $dbh->query("SELECT *
	// 						FROM `entry`
	// 						WHERE ")

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

	// using direct query
	$sql = "SELECT *
				FROM  `entry`
				WHERE  `traditional` LIKE  '%$term%'
				OR `simplified` LIKE '%$term%'
				OR `pinyin` LIKE '$term'";

	$result = $dbh->query($sql);


?>

<?php if($result) : ?>
	<table class="table">
		<?php foreach(($result) as $row) : ?>
			<tr>
				<td>
					<?php if ($chartype == "trad") : ?>
						<?php echo $row['traditional']; ?>
					<?php else : ?>
						<?php echo $row['simplified']; ?>
					<?php endif ?>
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
