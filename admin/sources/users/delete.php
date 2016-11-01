<?php
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_users WHERE id='$id'");
if($query->num_rows==0) {
	$redirect = './?a=users';
	header("Location: $redirect");
}
$row = $query->fetch_assoc();
?>
	<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Users / Delete</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if(isset($_GET['confirmed'])) {
								$delete = $db->query("DELETE * FROM ec_users WHERE id='$row[id]'");
								$delete = $db->query("DELETE * FROM ec_exchanges WHERE uid='$row[id]'");
								$delete = $db->query("DELETE * FROM ec_testimonials WHERE uid='$row[id]'");
								$delete = $db->query("DELETE * FROM ec_transactions WHERE uid='$row[id]'");
								echo success("User ($row[username]) was deleted.");
							} else {
								echo info("Are you sure you want to delete user ($row[username])?");
								echo '<a href="./?a=users&b=delete&id='.$row[id].'&confirmed=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
										<a href="./?a=users" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>