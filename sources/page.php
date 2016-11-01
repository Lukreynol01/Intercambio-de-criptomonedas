<?php
$prefix = protect($_GET['prefix']);
$query = $db->query("SELECT * FROM ec_pages WHERE prefix='$prefix'");
if($query->num_rows==0) { header("Location: $settings[url]"); }
$row = $query->fetch_assoc();
?>

	<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4><?php echo $row['title']; ?></h4>
							<?php echo $row['content']; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>