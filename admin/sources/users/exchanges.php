<?php
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_users WHERE id='$id'");
if($query->num_rows==0) {
	$redirect = './?a=users';
	header("Location: $redirect");
}
$r = $query->fetch_assoc();
?>
 <div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Users / Exchanges</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<form action="" method="POST">
								<div class="input-group">
								  <input type="text" class="form-control" name="qry" placeholder="Search by exchange id..." value="<?php if(isset($_POST['qry'])) { echo $_POST['qry']; } ?>">
								  <span class="input-group-btn">
									<button class="btn btn-default" type="submit"><i class="fa fa-search"></i> Search</button>
								  </span>
								</div><!-- /input-group -->
							</form>
							<br>
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="10%">User</th>
										<th width="15%">From</th>
										<th width="15%">To</th>
										<th width="10%">Amount</th>
										<th width="30%">Exchange ID</th>
										<th width="10%">Requested on</th>
										<th width="10%">Status</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$limit = 30;
									$startpoint = ($page * $limit) - $limit;
									if($page == 1) {
										$i = 1;
									} else {
										$i = $page * $limit;
									}
									if(isset($_POST['qry'])) {
										$qry = protect($_POST['qry']);
										$query = $db->query("SELECT * FROM ec_exchanges WHERE uid='$r[id]' and exchange_id LIKE '%$qry%' ORDER BY id");
									} else {
										$statement = "ec_exchanges WHERE uid='$r[id]'";
										$query = $db->query("SELECT * FROM {$statement} ORDER BY id LIMIT {$startpoint} , {$limit}");
									}
									if($query->num_rows>0) {
										while($row = $query->fetch_assoc()) {
											?>
											<tr>
												<td><?php echo $r['username']; ?></td>
												<td><?php echo getIcon($row['cfrom'],"20px","20px"); ?> <?php echo $row['cfrom']; ?></td>
												<td><?php echo getIcon($row['cto'],"20px","20px"); ?> <?php echo $row['cto']; ?></td>
												<td><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></td>
												<td><?php echo $row['exchange_id']; ?></td>
												<td><?php echo date("d/m/Y H:i",$row['created']); ?></td>
												<td><?php echo decodeStatus($row['status']); ?></td>
												<td>
													<a href="./?a=exchanges&b=explore&id=<?php echo $row['id']; ?>"><i class="fa fa-search"></i> Explore</a>
												</td>
											</tr>
											<?php
										}
									} else {
										if(isset($_POST['qry'])) {
											echo '<tr><td colspan="7">No found results for <b>'.$_POST[qry].'</b>.</td></tr>'; 
										} else {
											echo '<tr><td colspan="7">Still no exchanges.</td></tr>';
										}
									}
									?>
								</tbody>
							</table>
							<?php
							if(!$_POST['qry']) {
								$ver = "./?a=users&b=exchanges&id=$id";
								if(admin_pagination($statement,$ver,$limit,$page)) {
									echo admin_pagination($statement,$ver,$limit,$page);
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>