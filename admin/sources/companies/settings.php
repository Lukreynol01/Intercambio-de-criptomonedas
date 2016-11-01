<?php
$cid = protect($_GET['cid']);
$query = $db->query("SELECT * FROM ec_companies WHERE id='$cid'");
if($query->num_rows==0) { header("Location: ./?a=companies"); }
$row = $query->fetch_assoc();
?>
	<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Companies / <?php echo $row['name']; ?> / Settings</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if(isset($_POST['btn_save'])) { 
								$minimal_amount = protect($_POST['minimal_amount']);
								$maximum_amount = protect($_POST['maximum_amount']);
								$fee = protect($_POST['fee']);
								$include_fee = protect($_POST['include_fee']);
								if($include_fee == "yes") { $include_fee = 1; } else { $include_fee = 0; }
								
								if(empty($minimal_amount) or empty($maximum_amount)) { echo error("Minimal and maximum amount is required."); }
								elseif(!is_numeric($minimal_amount)) { echo error("Enter minimal amount with numbers."); }
								elseif(!is_numeric($maximum_amount)) { echo error("Enter maximum amount with numbers."); }
								else {
									$update = $db->query("UPDATE ec_companies SET minimal_amount='$minimal_amount',maximum_amount='$maximum_amount',include_fee='$include_fee',fee='$fee' WHERE id='$row[id]'");
									$query = $db->query("SELECT * FROM ec_companies WHERE id='$row[id]'");
									$row = $query->fetch_assoc();
									echo success("Your changes was saved successfully.");
								}
							}
							?>
							
							<form action="" method="POST">
								<div class="form-group">
									<label>Minimal amount</label>
									<input type="text" class="form-control" name="minimal_amount" value="<?php echo $row['minimal_amount']; ?>">
									<small>When client want to sell e-currencies from <?php echo $row['name']; ?> this will be minimal amount for exchange.</small>
								</div>
								<div class="form-group">
									<label>Maximum amount</label>
									<input type="text" class="form-control" name="maximum_amount" value="<?php echo $row['maximum_amount']; ?>">
									<small>When client want to sell e-currencies from <?php echo $row['name']; ?> this will be maximum amount for exchange.</small>
								</div>
								<div class="button-checkbox">
									<label><input type="checkbox" name="include_fee" <?php if($row['include_fee']=="1") { echo 'checked="checked"'; } ?> value="yes"> Include fee in total amount for payment</label>
								</div>
								<div class="form-group">
									<label>Fee</label>
									<input type="text" class="form-control" name="fee" value="<?php echo $row['fee']; ?>">
									<small>You can setup fixed or percentage fee for <?php echo $row['name']; ?>. This fee will be used to calculate total amount for payment for client. For example: If user sell 100 and if fee is 10% must pay 110 or if user sell 50 and fee is fixed 5.99 must pay 55.99 and etc. If do not want to setup fee leave blank.</small>
								</div>
								<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>