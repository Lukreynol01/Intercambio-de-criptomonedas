<?php
$cid = protect($_GET['cid']);
$query = $db->query("SELECT * FROM ec_companies WHERE id='$cid'");
if($query->num_rows==0) { header("Location: ./?a=companies"); }
$row = $query->fetch_assoc();
?> <div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Companies / <?php echo $row['name']; ?> / Receive list</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="25%">User sell</th>
										<th width="25%">User buy</th>
										<th width="25%">Status</th>
										<th width="25%">Currencies</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$list = explode(",",$row['receive_list']);
									foreach($list as $l) {
										?>
										<tr>
											<td><?php echo $row['name']; ?></td>
											<td><?php
												if (strpos($l,'//') !== false) {
													$exp = explode("//",$l);
													echo $exp[1];
												} else {
													echo $l;
												}
												?>
											</td>
											<td>
												<?php
												if (strpos($l,'//') !== false) {
													echo '<i class="fa fa-times"></i> Disallowed (<a href="./?a=companies&b=list&cid='.$cid.'&c=allow&company='.$l.'">Allown</a>)';
												} else {
													echo '<i class="fa fa-check"></i> Allowed (<a href="./?a=companies&b=list&cid='.$cid.'&c=disallow&company='.$l.'">Disallow</a>)';
												}
												?>
											</td>
											<td><?php $nums = $db->query("SELECT * FROM ec_currencies WHERE company_from='$row[name]' and company_to='$l'"); $nums = $nums->num_rows; echo $nums.' (<a href="./?a=companies&b=currencies&cid='.$row[id].'">Manage</a>)'; ?></td>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>