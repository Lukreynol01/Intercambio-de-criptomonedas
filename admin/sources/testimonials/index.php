 <div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Testimonials</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="15%">From</th>
										<th width="60%">Feedback</th>
										<th width="20%">Status</th>
										<th width="5%">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$limit = 20;
									$startpoint = ($page * $limit) - $limit;
									if($page == 1) {
										$i = 1;
									} else {
										$i = $page * $limit;
									}
									$statement = "ec_testimonials";
									$query = $db->query("SELECT * FROM {$statement} ORDER BY status, id LIMIT {$startpoint} , {$limit}");
									if($query->num_rows>0) {
										while($row = $query->fetch_assoc()) {
											?>
											<tr>
												<td><?php echo idinfo($row['uid'],"username"); ?></td>
												<td><?php echo $row['content']; ?></td>
												<td><?php if($row['status'] == "0") { echo 'Pending'; } else { echo 'Published'; } ?></td>
												<td>
												  <?php
												  if($row['status'] == "0") {
												  ?>
													<a href="./?a=testimonials&b=approve&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Approve"><i class="fa fa-check"></i></a> 
													<a href="./?a=testimonials&b=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-times"></i></a>
												  <?php 
												  } else {
													echo '-';
												  }
												  ?>
												</td>
											</tr>
											<?php
										}
									} else {
										echo '<tr><td colspan="4">Still no testimonials.</td></tr>';
									}
									?>
								</tbody>
							</table>
							<?php
							$ver = "./?a=testimonials";
							if(admin_pagination($statement,$ver,$limit,$page)) {
								echo admin_pagination($statement,$ver,$limit,$page);
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>