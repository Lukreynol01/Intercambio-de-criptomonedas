 <div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Pages</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<a href="./?a=pages&b=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
							<br><br>
							<table class="table table-hover">
							<thead>
								<tr>
									<td width="35%">Title</td>
									<td width="25%">Page prefix</td>
									<td width="35%">Page link</td>
									<td width="5%">Action</td>
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
								$statement = "ec_pages";
								$query = $db->query("SELECT * FROM {$statement} ORDER BY id LIMIT {$startpoint} , {$limit}");
								if($query->num_rows>0) {
									while($row = $query->fetch_assoc()) {
										?>
										<tr>
											<td><?php echo $row['title'] ?></td>
											<td><?php echo $row['prefix']; ?></td>
											<td><a href="<?php echo $settings['url']; ?>page/<?php echo $row['prefix']; ?>" target="_blank"><?php echo $settings['url']; ?>page/<?php echo $row['prefix']; ?></a></td>
											<td>
												<a href="./?a=pages&b=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil"></i></a> 
												<?php if($row['prefix'] !== "terms-of-service" && $row['prefix'] !== "privacy-policy") { ?>
												<a href="./?a=pages&b=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-times"></i></a>
												<?php } ?>
											</td>
										</tr>
										<?php
									}
								} else {
									echo '<tr><td colspan="4">Still no pages.</td></tr>';
								}
								?>
							</tbody>
						</table>
						<?php
						$ver = "./?a=faq";
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