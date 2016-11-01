<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Companies / <?php echo $row['name']; ?> / Receive list / Disallow</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							$cid = protect($_GET['cid']);
							$company = protect($_GET['company']);
							$query = $db->query("SELECT * FROM ec_companies WHERE id='$cid'");
							if($query->num_rows>0) {
								$row = $query->fetch_assoc();
								$receive_list = $row['receive_list'];
								$from = $company;
								$to = '//'.$company;
								$receive_list = str_ireplace($from,$to,$receive_list);
								$update = $db->query("UPDATE ec_companies SET receive_list='$receive_list' WHERE id='$row[id]'");
								echo success("Now your users can not receive money from $row[name] to $company. Redirecting..");
								echo '<META http-equiv="refresh" content="3;URL=./?a=companies&b=list&cid='.$cid.'">';
							} else {
								header("Location: ./?a=companies&b=list&cid=$cid");
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>