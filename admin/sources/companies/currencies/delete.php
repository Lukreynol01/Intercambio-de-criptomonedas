<?php
$cid = protect($_GET['cid']);
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_currencies WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=companies&b=currencies&cid=$cid"); }
$row = $query->fetch_assoc();
$companyQuery = $db->query("SELECT * FROM ec_companies WHERE id='$row[cid]'");
$row2 = $companyQuery->fetch_assoc();
?> 
<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Companies / <?php echo $row['company_from']; ?> / Currencies / Delete</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if(isset($_GET['confirmed'])) {
								$delete = $db->query("DELETE FROM ec_currencies WHERE id='$id'");
								echo success("Currency $row[company_from] ($row[currency_from]) = $row[company_to] ($row[currency_to]) was deleted.");
							} else {
								echo info("Are you sure you want to delete currency $row[company_from] ($row[currency_from]) = $row[company_to] ($row[currency_to])?");
								echo '<a href="./?a=companies&b=currencies&cid='.$cid.'&c=delete&id='.$row[id].'&confirmed=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
										<a href="./?a=companies&b=currencies&cid='.$cid.'" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>