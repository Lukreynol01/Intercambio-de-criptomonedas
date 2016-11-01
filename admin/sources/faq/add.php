 <div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">FAQ / Add</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if(isset($_POST['btn_add'])) {
								$title = protect($_POST['title']);
								$content = $_POST['content'];
								$check = $db->query("SELECT * FROM ec_faq WHERE question='$title'");
								if(empty($title) or empty($content)) { echo error("All fields are required."); }
								elseif($check->num_rows>0) { echo error("Already exists question with this title."); }
								else {	
									$insert = $db->query("INSERT ec_faq (question,answer) VALUES ('$title','$content')");
									echo success("Question ($title) was added successfully.");
								}
							}
							?>
							<form action="" method="POST">
								<div class="form-group">
									<label>Question</label>
									<input type="text" class="form-control" name="title">
								</div>
								<div class="form-group">
									<label>Answer</label>
									<textarea class="form-control" rows="15" name="content"></textarea>
								</div>
								<button type="submit" class="btn btn-primary" name="btn_add"><i class="fa fa-plus"></i> Add</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>