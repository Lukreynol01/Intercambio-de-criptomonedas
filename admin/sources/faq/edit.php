<?php
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_faq WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=faq"); }
$row = $query->fetch_assoc();
?>
<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">FAQ / Edit</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if(isset($_POST['btn_save'])) {
								$title = protect($_POST['title']);
								$content = $_POST['content'];
								$check = $db->query("SELECT * FROM ec_faq WHERE question='$title'");
								if(empty($title) or empty($content)) { echo error("All fields are required."); }
								elseif($row['question'] !== $title && $check->num_rows>0) { echo error("Already exists question with this title."); }
								else {	
									$update = $db->query("UPDATE ec_faq SET question='$title',answer='$content' WHERE id='$id'");
									echo success("Your changes was saved successfully.");
									$query = $db->query("SELECT * FROM ec_faq WHERE id='$id'");
									$row = $query->fetch_assoc();
								}
							}
							?>
							<form action="" method="POST">
								<div class="form-group">
									<label>Question</label>
									<input type="text" class="form-control" name="title" value="<?php echo $row['question']; ?>">
								</div>
								<div class="form-group">
									<label>Answer</label>
									<textarea class="form-control" rows="15" name="content"><?php echo $row['answer']; ?></textarea>
								</div>
								<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>