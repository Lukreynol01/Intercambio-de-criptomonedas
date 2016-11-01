<h4><?php echo $lang['faq']; ?></h4>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<?php
				$query = $db->query("SELECT * FROM ec_faq ORDER BY id"); 
				if($query->num_rows>0) {
				  while($row = $query->fetch_assoc()) {
				  ?>
				  <div class="panel panel-default">
					<div class="panel-heading" role="tab" id="heading_<?php echo $row['id']; ?>">
					  <h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $row['id']; ?>" aria-expanded="true" aria-controls="collapseOne">
						  <?php echo $row['question']; ?>
						</a>
					  </h4>
					</div>
					<div id="collapse_<?php echo $row['id']; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_<?php echo $row['id']; ?>">
					  <div class="panel-body">
						<?php echo nl2br($row['answer']); ?>
					  </div>
					</div>
				  </div>
				<?php 
				}
			   }
			   ?>
			</div>