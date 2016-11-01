<?php
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_testimonials WHERE id='$id'");
if($query->num_rows==0) {
	$redirect = './?a=testimonials';
	header("Location: $redirect");
}
$row = $query->fetch_assoc();
?>
	<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Testimonials / Delete</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if(isset($_GET['confirmed'])) {
								$user = idinfo($row['uid'],"username");
								$delete = $db->query("DELETE FROM ec_testimonials WHERE id='$id'");
								echo success("Testimonial from ($user) was deleted.");
							} else {
								$user = idinfo($row['uid'],"username");
								echo info("Are you sure you want to delete testimonial from ($user)?");
								echo '<a href="./?a=testimonials&b=delete&id='.$row[id].'&confirmed=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
										<a href="./?a=testimonials" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>