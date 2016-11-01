<?php
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=exchanges"); }
$row = $query->fetch_assoc();
?>
<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Exchange / Delete</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
								if(isset($_GET['confirmed'])) {
									$delete = $db->query("DELETE FROM ec_exchanges WHERE id='$id'");
									echo success("Page ($row[exchange_id]) was deleted.");
								} else {
									echo info("Are you sure you want to delete page ($row[exchange_id])?");
									echo '<a href="./?a=exchanges&b=delete&id='.$row[id].'&confirmed=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
											<a href="./?a=exchanges" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
								}
							
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>
