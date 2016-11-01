 <div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Pages / Add</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if(isset($_POST['btn_add'])) {
								$title = protect($_POST['title']);
								$prefix = protect($_POST['prefix']);
								$content = $_POST['content'];
								$check = $db->query("SELECT * FROM ec_pages WHERE prefix='$prefix'");
								if(empty($title) or empty($content) or empty($prefix)) { echo error("All fields are required."); }
								elseif(!isValidUsername($prefix)) { echo error("Please enter valid prefix. Use latin characters and symbols - and _. Do not make spaces between words."); }
								elseif($check->num_rows>0) { echo error("Already exists page with this prefix."); }
								else {	
									$insert = $db->query("INSERT ec_pages (title,content,prefix) VALUES ('$title','$content','$prefix')");
									$pagelink = '<a href="'.$settings[url].'page/'.$prefix.'" target="_blank">'.$settings[url].'page/'.$prefix.'</a>';
									echo success("Page ($title) was added successfully. Page link: $pagelink");
								}
							}
							?>
							<form action="" method="POST">
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="title">
								</div>
								<div class="form-group">
									<label>Prefix</label>
									<div class="input-group">
									  <span class="input-group-addon"><?php echo $settings['url']; ?>page/</span>
									  <input type="text" class="form-control" name="prefix">
									</div>
									<small>Use latin characters and symbols - and _. Do not make spaces between words.</small>
								</div>
								<div class="form-group">
									<label>Content</label>
									<textarea class="cleditor" rows="15" name="content"></textarea>
								</div>
								<button type="submit" class="btn btn-primary" name="btn_add"><i class="fa fa-plus"></i> Add</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>