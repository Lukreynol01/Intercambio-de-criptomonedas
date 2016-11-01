  <style type="text/css">
 .form-myselect {
  display: block;
  width: 100%;
  height: 34px;
  padding: 6px 12px;
  font-size: 14px;
  line-height: 1.42857143;
  color: #555;
  background-color: #fff;
  background-image: none;
  border: 1px solid #ccc;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
  -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
       -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
          transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
 </style>

<!--<script>
function myFunction() {
    document.getElementById("myForm").submit();
}
</script>-->

<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Exchanges</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<form action="" method="POST">
								<div class="input-group">
								  <input type="text" class="form-control" name="qry" placeholder="Search by exchange id" value="<?php if(isset($_POST['qry'])) { echo $_POST['qry']; } ?>">
								  <span class="input-group-btn">
									<button class="btn btn-default" type="submit"><i class="fa fa-search"></i> Search</button>
								  </span>
								</div><!-- /input-group -->
							</form>
							<br><div id="busqueda">
							<table class="table table-hover" id="tablaclientes">
								<thead>
									<tr>
										<th width="10%">User</th>
										<th width="15%">From</th>
										<th width="15%">To</th>
										<th width="10%">Amount</th>
										<th width="20%">Exchange ID</th>
										<th width="10%">Requested on</th>
                                                                                        <th width="10%"><form action="" method="POST"><select class="form-myselect" type="text" name="buscador" value="<?php echo $_POST['buscador']; ?>" onchange="this.form.submit()">
                                                                                            <option value="">Status</option>
                                                                                            <option value="1">Esperando confirmacion</option>
                                                                                            <option value="2">Esperando pago</option>
                                                                                            <option value="3">Procesando</option>
                                                                                            <option value="4">Procesado</option>
                                                                                            <option value="5">Rechazado</option>
                                                                                            <option value="6">Cancelado</option>
                                                                                                </select></form>
                                                                                            </th>
										<th width="10%">Action</th>
                                                                                <th></th>
									</tr>
								</thead>
								<tbody>
                                                                
									<?php
									$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
									$limit = 30;
									$startpoint = ($page * $limit) - $limit;
									if($page == 1) {
										$i = 1;
									} else {
										$i = $page * $limit;
									}
									if(isset($_POST['qry'])) {
										$qry = protect($_POST['qry']);
										$query = $db->query("SELECT * FROM ec_exchanges WHERE exchange_id LIKE '%$qry' ORDER BY id DESC");
									} elseif(isset($_POST['buscador'])) {
										$busc = protect($_POST['buscador']);
										$query = $db->query("SELECT * FROM ec_exchanges WHERE status = '$busc' ORDER BY id DESC");
									} else {
										$statement = "ec_exchanges";
										$query = $db->query("SELECT * FROM {$statement} ORDER BY id DESC LIMIT {$startpoint} , {$limit}");
									}
									if($query->num_rows>0) {
										while($row = $query->fetch_assoc()) {
											?>
											<tr>
												<td><?php if($row['uid']) { echo '<a href="./?a=users&b=exchanges&id='.$row[uid].'">'.idinfo($row[uid],"username").'</a>'; } else { echo 'Anonymous'; } ?></td>
												<td><?php echo getIcon($row['cfrom'],"20px","20px"); ?> <?php echo $row['cfrom']; ?></td>
												<td><?php echo getIcon($row['cto'],"20px","20px"); ?> <?php echo $row['cto']; ?></td>
												<td><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></td>
												<td><?php echo $row['exchange_id']; ?></td>
												<td><?php echo date("d/m/Y H:i",$row['created']); ?></td>
												<td><?php echo decodeStatus($row['status']); ?></td>
												<td>
													<a href="./?a=exchanges&b=explore&id=<?php echo $row['id']; ?>"><i class="fa fa-search"></i> Explore</a>
												</td>
                                                                                                <td>
												<a href="./?a=exchanges&b=explore&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil"></i></a> 
												<a href="./?a=exchanges&b=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-times"></i></a>
												
											</td>
											</tr>
											<?php
										}
									} else {
										if(isset($_POST['qry'])) {
											echo '<tr><td colspan="7">No found results for <b>'.$_POST[qry].'</b>.</td></tr>'; 
										} else {
											echo '<tr><td colspan="7">Still no exchange orders.</td></tr>';
										}
									}
									?>
								</tbody>
                                                        </table></div>
                                                        
                                                        
   
							<?php
							if(!$_POST['qry']) {
								$ver = "./?a=exchanges";
								if(admin_pagination($statement,$ver,$limit,$page)) {
									echo admin_pagination($statement,$ver,$limit,$page);
								}
							}
                                                        if(!$_POST['buscador']) {
								$ver = "./?a=exchanges";
								if(admin_pagination($statement,$ver,$limit,$page)) {
									echo admin_pagination($statement,$ver,$limit,$page);
								}
							}
							?>
						</div>
					</div>