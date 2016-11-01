<div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Dashboard</h4>

                </div>

            </div>
			
            <div class="row">
                 <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="<?php echo $settings['url']; ?>admin/?a=users"><div class="dashboard-div-wrapper bk-clr-one" >
                        <i  class="fa fa-users dashboard-div-icon" ></i>
                        <div class="progress progress-striped active">
						  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; heith:2px">
						  </div>
												   
						</div>
                         <h5><?php $nums = $db->query("SELECT * FROM ec_users ORDER BY id"); $nums = $nums->num_rows; if($nums==1) { echo '1 user'; } else { echo $nums.' users'; } ?></h5>
                    </div></a>
                </div>
                 <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="<?php echo $settings['url']; ?>admin/?a=exchanges"><div class="dashboard-div-wrapper bk-clr-two">
                        <i  class="fa fa-refresh dashboard-div-icon" ></i>
                        <div class="progress progress-striped active">
						  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						  </div>
												   
						</div>
						<h5><?php $nums = $db->query("SELECT * FROM ec_exchanges ORDER BY id"); $nums = $nums->num_rows; if($nums==1) { echo '1 exchange'; } else { echo $nums.' exchanges'; } ?></h5>
                   </div></a>
                </div>
                 <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="#"><div class="dashboard-div-wrapper bk-clr-three">
                        <i  class="fa fa-check dashboard-div-icon" ></i>
                        <div class="progress progress-striped active">
						  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						  </div>
												   
						</div>
						<h5><?php $nums = $db->query("SELECT * FROM ec_exchanges WHERE status='4' ORDER BY id"); $nums = $nums->num_rows; if($nums==1) { echo '1 processed exchange'; } else { echo $nums.' processed exchanges'; } ?></h5>

                    </div></a>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <a href="<?php echo $settings['url']; ?>admin/?a=testimonials"><div class="dashboard-div-wrapper bk-clr-four">
                        <i  class="fa fa-comments-o dashboard-div-icon" ></i>
                        <div class="progress progress-striped active">
						  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						  </div>
												   
						</div>
						<h5><?php $nums = $db->query("SELECT * FROM ec_testimonials ORDER BY id"); $nums = $nums->num_rows; if($nums==1) { echo '1 testimonial'; } else { echo $nums.' testimonials'; } ?></h5>
                    </div></a>
                </div>

            </div>
            <div class="row">
				<div class="col-md-12">
			<?php
			if(isset($_POST['btn_update_rate'])) {
				$currency_from = protect($_POST['currency_from']);
				$currency_to = protect($_POST['currency_to']);
				$rate_from = protect($_POST['rate_from']);
				$rate_to = protect($_POST['rate_to']);
				if(empty($currency_from)) { echo error("Select on which currency you want to change rate."); }
				elseif(empty($currency_to)) { echo error("Select on which currency you want to change rate."); }
				elseif(!is_numeric($rate_from)) { echo error("Enter rate with numbers."); }
				elseif(!is_numeric($rate_to)) { echo error("Enter rate with numbers."); }
				else {
					$update = $db->query("UPDATE ec_currencies SET rate_from='$rate_from',rate_to='$rate_to' WHERE currency_from='$currency_from' and currency_to='$currency_to'");
					echo success("Rate was changed to $rate_from $currency_from = $rate_to $currency_to");
				}
			}
								
			if(isset($_POST['btn_update_reserve'])) {
				$currency_from = protect($_POST['c_from']);
				$currency_to = protect($_POST['c_to']);
				$reserve = protect($_POST['reserve']);
				if(empty($currency_from)) { echo error("Select on which currency you want to change reserve."); }
				elseif(empty($currency_to)) { echo error("Select on which currency you want to change reserve."); }
				elseif(!is_numeric($reserve)) { echo error("Enter reserve with numbers."); }
				else {
					$update = $db->query("UPDATE ec_currencies SET reserve='$reserve' WHERE currency_from='$currency_from' and currency_to='$currency_to'");
					echo success("Reserve was changed to $reserve $currency_to");
				}	
			}
							
			?>
			</div>
                <div class="col-md-8">
         
                        <div class="panel panel-default">
                            <div class="panel-heading">
								Waiting exchanges to be processed
                            </div>
                            <div class="panel-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>From</th>
											<th>To</th>
											<th>Amount</th>
											<th>User</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$query = $db->query("SELECT * FROM ec_exchanges WHERE status='3' ORDER BY id DESC LIMIT 10");
									if($query->num_rows>0) {
										while($row = $query->fetch_assoc()) {
										?>
										<tr>
											<td><?php echo getIcon($row['cfrom'],"20px","20px"); ?> <?php echo $row['cfrom']; ?></td>
											<td><?php echo getIcon($row['cto'],"20px","20px"); ?> <?php echo $row['cto']; ?></td>
											<td><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></td>
											<td><?php if($row['uid']) { echo '<a href="./?a=users&b=exchanges&id='.$row[uid].'">'.idinfo($row[uid],"username").'</a>'; } else { echo 'Anonymous ('.$row[ip].')'; } ?></td>
											<td>
												<a href="./?a=exchanges&b=explore&id=<?php echo $row['id']; ?>"><i class="fa fa-search"></i> Explore</a>
											</td>
										</tr>
										<?php
										}
									} else {
										echo '<tr><td colspan="5">Still no requests for exchanges to be processed.</td></tr>';
									}
									?>
									</tbody>
								</table>
                            </div>
                        </div>
						
						<div class="panel panel-default">
                            <div class="panel-heading">
								Waiting testimonials to be approved
                            </div>
                            <div class="panel-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>From</th>
											<th>Feedback</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$query = $db->query("SELECT * FROM ec_testimonials WHERE status='0' ORDER BY id");
										if($query->num_rows>0) {
											while($row = $query->fetch_assoc()) {
												echo '<tr>
														<td><a href="./?a=users&b=exchanges&id='.$row[uid].'">'.idinfo($row[uid],"username").'</a></td>
														<td>'.$row[content].'</td>
														<td>
															<a href="./?a=testimonials&b=approve&id='.$row[id].'"><i class="fa fa-check"></i></a> 
															<a href="./?a=testimonials&b=delete&id='.$row[id].'"><i class="fa fa-times"></i></a>
														</td>
													</tr>';
											}
										} else {
											echo '<tr><td colspan="3">Still no new testimonials for approval.</td></tr>';
										}
										?>
									</tbody>
								</table>
                            </div>
                        </div>
 
                </div>
                <div class="col-md-4">
             
						<div class="panel panel-default">
							<div class="panel-heading">
								Update exchange rate
							</div>
							<div class="panel-body">

								<form action="" method="POST">
									<div class="form-group">
										<label>Currency from</label>
										<select class="form-control" name="currency_from" id="currency_from" onchange="getCurrencies(this.value);">
											<option value=""></option>
											<?php
											$query = $db->query("SELECT * FROM ec_currencies ORDER BY id");
											while($row = $query->fetch_assoc()) {
												if (strpos($list,$row['currency_from']) !== false) { } else {
													echo '<option value="'.$row[currency_from].'">'.$row[currency_from].'</option>';
												}
												$list .= $row['currency_from'].",";
											}
											?>
										</select>
									</div>
									<div class="form-group">
										<label>Currency to</label>
										<select class="form-control" name="currency_to" id="currency_to" onchange="getCurrentRate();">
											
										</select>
									</div>
									<div class="form-group">
										<label>Currenct exchange rate</label>
										<input type="text" class="form-control" disabled id="current_rate">
									</div>
									<div class="form-group">
										<label>Rate from</label>
										<div class="input-group">
										  <input type="text" class="form-control" name="rate_from">
										  <span class="input-group-addon" id="currency_code_from">-</span>
										</div>
									</div>
									<div class="form-group">
										<label>Rate to</label>
										<div class="input-group">
										  <input type="text" class="form-control" name="rate_to">
										  <span class="input-group-addon" id="currency_code_to">-</span>
										</div>
									</div>
									<button type="submit" class="btn btn-success" name="btn_update_rate"><i class="fa fa-check"></i> Update rate</button>
								</form>
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading">
								Update reserve
							</div>
							<div class="panel-body">
								<form action="" method="POST">
									<div class="form-group">
										<label>Currency from</label>
										<select class="form-control" name="c_from" id="c_from" onchange="getCurrencies2(this.value);">
											<option value=""></option>
											<?php
											$query2 = $db->query("SELECT * FROM ec_currencies ORDER BY id");
											while($row2 = $query2->fetch_assoc()) {
												if (strpos($list2,$row2['currency_from']) !== false) { } else {
													echo '<option value="'.$row2[currency_from].'">'.$row2[currency_from].'</option>';
												}
												$list2 .= $row2['currency_from'].",";
											}
											?>
										</select>
									</div>
									<div class="form-group">
										<label>Currency to</label>
										<select class="form-control" name="c_to" id="c_to" onchange="getCurrentReserve();">
											
										</select>
									</div>
									<div class="form-group">
										<label>Currenct reserve</label>
										<input type="text" class="form-control" disabled id="current_reserve">
									</div>
									<div class="form-group">
										<label>Reserve</label>
										<div class="input-group">
										  <input type="text" class="form-control" name="reserve">
										  <span class="input-group-addon" id="c_reserve">-</span>
										</div>
									</div>
									<button type="submit" class="btn btn-success" name="btn_update_reserve"><i class="fa fa-check"></i> Update reserve</button>
								</form>
							</div>
						</div>
                </div>
            </div>
        </div>
    </div>