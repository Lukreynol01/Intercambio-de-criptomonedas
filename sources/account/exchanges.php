<h4><?php echo $lang['my_exchanges']; ?></h4>
<table class="table table-hover">
	<thead>
		<tr>
			<th><?php echo $lang['from']; ?></th>
			<th><?php echo $lang['to']; ?></th>
			<th><?php echo $lang['amount']; ?></th>
			<th>Exchange ID</th>
			<th><?php echo $lang['status']; ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 15;
	$startpoint = ($page * $limit) - $limit;
	if($page == 1) {
		$i = 1;
	} else {
		$i = $page * $limit;
	}
	$statement = "ec_exchanges WHERE uid='$_SESSION[ec_uid]'";
	$query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
	if($query->num_rows>0) {
									while($row = $query->fetch_assoc()) {
									?>
									<tr>
										<td><?php echo getIcon($row['cfrom'],"20px","20px"); ?> <?php echo $row['cfrom']; ?></td>
										<td><?php echo getIcon($row['cto'],"20px","20px"); ?> <?php echo $row['cto']; ?></td>
										<td><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></td>
										<td>
											<a href="<?php echo $settings['url']; ?>exchange/<?php echo $row['exchange_id']; ?>">
												<?php
												$string = $row['exchange_id'];
												if(strlen($string) > 20) $string = substr($string, 0, 20).'...';
												echo $string;
												?>
											</a>
										</td>
										<td><?php echo decodeStatus($row['status']); ?></td>
									</tr>
									<?php
									}
								} else {
									echo '<tr><td colspan="5">'.$lang[still_no_exchanges].'</td></tr>';
								}
	?>
  </tbody>
</table>

<?php
$ver = $settings['url']."account/exchanges";
if(web_pagination($statement,$ver,$limit,$page)) {
	echo web_pagination($statement,$ver,$limit,$page);
}
?>