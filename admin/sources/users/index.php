 <div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Users</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<form action="" method="POST">
								<div class="input-group">
								  <input type="text" class="form-control" name="qry" placeholder="Search by username or email address..." value="<?php if(isset($_POST['qry'])) { echo $_POST['qry']; } ?>">
								  <span class="input-group-btn">
									<button class="btn btn-default" type="submit"><i class="fa fa-search"></i> Search</button>
								  </span>
								</div><!-- /input-group -->
							</form>
							<br>
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="15%">Username</th>
										<th width="15%">Email address</th>
										<th width="15%">IP Address</th>
										<th width="10%">Status</th>
										<th width="10%">Exchanges</th>
										<th width="15%">Registered on</th>
										<th width="15%">Last activity</th>
										<th width="5%">Action</th>
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
										$query = $db->query("SELECT * FROM ec_users WHERE name LIKE '%$qry%' or username LIKE '%$qry' or email LIKE '%$qry' ORDER BY id");
									} else {
										$statement = "ec_users";
										$query = $db->query("SELECT * FROM {$statement} ORDER BY id LIMIT {$startpoint} , {$limit}");
									}
									if($query->num_rows>0) {
										while($row = $query->fetch_assoc()) {
											?>
											<tr>
												<td><?php echo $row['username']; ?></td>
												<td><?php echo $row['email']; ?></td>
												<td><?php echo $row['ip']; ?></td>
												<td><?php if($row['status'] == "1") { echo '<span class="label label-success">Active</span>'; } elseif($row['status'] == "2") { echo '<span class="label label-danger">Blocked</span>'; } elseif($row['status'] == "666") { echo '<span class="label label-info">Admin</span>'; } else { echo '<span class="label label-default">Unknown</span>'; } ?></td>
												<td><a href="./?a=users&b=exchanges&id=<?php echo $row['id']; ?>"><?php $nums = $db->query("SELECT * FROM ec_exchanges WHERE uid='$row[id]'"); $nums = $nums->num_rows; echo $nums; ?></a></td>
												<td><span class="label label-success"><?php echo date("d/m/Y H:i",$row['created']); ?></span></td>
												<td><span class="label label-info"><?php echo date("d/m/Y H:i",$row['updated']); ?></span></td>
												<td>
													<a href="./?a=users&b=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil"></i></a> 
													<a href="./?a=users&b=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-times"></i></a>
												</td>
											</tr>
											<?php
										}
									} else {
										if(isset($_POST['qry'])) {
											echo '<tr><td colspan="8">No found results for <b>'.$_POST[qry].'</b>.</td></tr>'; 
										} else {
											echo '<tr><td colspan="8">Still no users.</td></tr>';
										}
									}
									?>
								</tbody>
							</table>
							<?php
							if(!$_POST['qry']) {
								$ver = "./?a=users";
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