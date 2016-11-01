 <div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Companies</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="15%">Name</th>
										<th width="10%">Status</th>
										<th width="15%">Account</th>
										<th width="15%">Currencies</th>
										<th width="10%">Min. amount</th>
										<th width="10%">Max. amount</th>
										<th width="10%">Fee</th>
										<th width="15%">Receive list</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$query = $db->query("SELECT * FROM ec_companies ORDER BY id"); 
									if($query->num_rows>0) {
										while($row = $query->fetch_assoc()) {
											?>
											<tr>
												<td><?php echo $row['name']; ?></td>
												<td><?php if($row['allow_send'] == 1) { echo '<i class="fa fa-check"></i> Allowed (<a href="./?a=companies&b=disallow&cid='.$row[id].'">Disallow</a>)'; } else { echo '<i class="fa fa-times"></i> Disallowed (<a href="./?a=companies&b=allow&cid='.$row[id].'">Allow</a>)'; } ?></td>
												<td><?php if($row['a_field_1']) { echo '<i class="fa fa-check"></i> Setted (<a href="./?a=companies&b=account&cid='.$row[id].'">Modify</a>)'; } else { echo '<i class="fa fa-times"></i> Not yet (<a href="./?a=companies&b=account&cid='.$row[id].'">Setup</a>)'; } ?></td>
												<td><?php $nums = $db->query("SELECT * FROM ec_currencies WHERE company_from='$row[name]'"); $nums = $nums->num_rows; echo $nums.' (<a href="./?a=companies&b=currencies&cid='.$row[id].'">Manage</a>)'; ?></td>
												<td><?php echo $row['minimal_amount']; ?> (<a href="./?a=companies&b=settings&cid=<?php echo $row['id']; ?>">Edit</a>)</td>
												<td><?php echo $row['maximum_amount']; ?> (<a href="./?a=companies&b=settings&cid=<?php echo $row['id']; ?>">Edit</a>)</td>
												<td><?php if(empty($row['fee'])) { echo '0'; } else { echo $row['fee']; } ?>  (<a href="./?a=companies&b=settings&cid=<?php echo $row['id']; ?>">Edit</a>)</td>
												<td>
													<?php
													$list = explode(",",$row['receive_list']);
													$i = 0;
													foreach($list as $l) {
														if (strpos($l,'//') !== false) { } else {
															$i++;
														}
													}
													echo $i;
													?> (<a href="./?a=companies&b=list&cid=<?php echo $row['id']; ?>">Manage</a>)
												</td>
											</tr>
											<?php
										}
									} else {
										echo '<tr><td colspan="8">Still no companies.</td></tr>';
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