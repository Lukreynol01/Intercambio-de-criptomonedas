<?php
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_pages WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=pages"); }
$row = $query->fetch_assoc();
?>
<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Pages / Edit</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if(isset($_POST['btn_save'])) {
								$title = protect($_POST['title']);
								if($row['prefix'] == "terms-of-service" or $row['prefix'] == "about" or $row['prefix'] == "privacy-policy") {
									$prefix = $row['prefix'];
								} else {
									$prefix = protect($_POST['prefix']);
								}
								$content = $_POST['content'];
								$check = $db->query("SELECT * FROM ec_pages WHERE prefix='$prefix'");
								if(empty($title) or empty($content) or empty($prefix)) { echo error("All fields are required."); }
								elseif(!isValidUsername($prefix)) { echo error("Please enter valid prefix. Use latin characters and symbols - and _. Do not make spaces between words."); }
								elseif($row['prefix'] !== $prefix && $check->num_rows>0) { echo error("Already exists page with this prefix."); }
								else {	
									$update = $db->query("UPDATE ec_pages SET title='$title',content='$content',prefix='$prefix' WHERE id='$id'");
									$pagelink = '<a href="'.$settings[url].'page/'.$prefix.'" target="_blank">'.$settings[url].'page/'.$prefix.'</a>';
									echo success("Your changes was saved successfully. Page link: $pagelink");
									$query = $db->query("SELECT * FROM ec_pages WHERE id='$id'");
									$row = $query->fetch_assoc();
								}
							}
							?>
							<form action="" method="POST">
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="title" value="<?php echo $row['title']; ?>">
								</div>
								<div class="form-group">
									<label>Prefix</label>
									<div class="input-group">
									  <span class="input-group-addon"><?php echo $settings['url']; ?>page/</span>
									  <input type="text" class="form-control" name="prefix" <?php if($row['prefix'] == "terms-of-service" or $row['prefix'] == "about" or $row['prefix'] == "privacy-policy") { echo 'disabled'; } ?> value="<?php echo $row['prefix']; ?>">
									</div>
									<small>Use latin characters and symbols - and _. Do not make spaces between words.</small>
								</div>
								<div class="form-group">
									<label>Content</label>
									<textarea class="cleditor" rows="15" name="content"><?php echo $row['content']; ?></textarea>
								</div>
								<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>