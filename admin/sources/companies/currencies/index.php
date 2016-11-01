<?php
$cid = protect($_GET['cid']);
$query = $db->query("SELECT * FROM ec_companies WHERE id='$cid'");
if($query->num_rows==0) { header("Location: ./?a=companies"); }
$row = $query->fetch_assoc();
?> <div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Companies / <?php echo $row['name']; ?> / Currencies</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<a href="./?a=companies&b=currencies&cid=<?php echo $cid; ?>&c=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
							<br><br>
							<table class="table table-hover">
								<thead>
									<tr>
										<th width="25%">From</th>
										<th width="25%">To</th>
										<th width="25%">Exchange rate</th>
										<th width="20%">Reserve</th>
										<th width="5%">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$getCurrencies = $db->query("SELECT * FROM ec_currencies WHERE company_from='$row[name]' ORDER BY id");
									if($getCurrencies->num_rows>0) {
										while($get = $getCurrencies->fetch_assoc()) {
											?>
												<tr>
													<td><?php echo $get['company_from']; ?></td>
													<td><?php echo $get['company_to']; ?></td>
													<td><?php echo $get['rate_from']." ".$get['currency_from']." = ".$get['rate_to']." ".$get['currency_to']; ?></td>
													<td><?php echo $get['reserve']." ".$get['currency_to']; ?></td>
													<td>
														<a href="./?a=companies&b=currencies&cid=<?php echo $cid; ?>&c=edit&id=<?php echo $get['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil"></i></a> 
														<a href="./?a=companies&b=currencies&cid=<?php echo $cid; ?>&c=delete&id=<?php echo $get['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-times"></i></a>
													</td>
											<?php
										}
									} else {
										echo '<tr><td colspan="5">Still no currencies.</td></tr>';
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