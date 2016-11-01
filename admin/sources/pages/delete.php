<?php
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_pages WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=pages"); }
$row = $query->fetch_assoc();
?>
<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Pages / Delete</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if($row['prefix'] == "terms-of-service" or $row['prefix'] == "privacy-policy") {
								echo error("You can not delete page $row[title].");
							} else {
								if(isset($_GET['confirmed'])) {
									$delete = $db->query("DELETE FROM ec_pages WHERE id='$id'");
									echo success("Page ($row[title]) was deleted.");
								} else {
									echo info("Are you sure you want to delete page ($row[title])?");
									echo '<a href="./?a=pages&b=delete&id='.$row[id].'&confirmed=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
											<a href="./?a=pages" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>