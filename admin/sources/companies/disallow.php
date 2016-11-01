<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Companies / Disallow</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							$cid = protect($_GET['cid']);
							$query = $db->query("SELECT * FROM ec_companies WHERE id='$cid'");
							if($query->num_rows>0) {
								$row = $query->fetch_assoc();
								$update = $db->query("UPDATE ec_companies SET allow_send='0' WHERE id='$row[id]'");
								echo success("Now your users can not send money to $row[name]. Redirecting..");
								echo '<META http-equiv="refresh" content="3;URL=./?a=companies">';
							} else {
								header("Location: ./?a=companies");
							}
							?>
					</div>
					</div>
				</div>
			</div>
		</div>
   </div>