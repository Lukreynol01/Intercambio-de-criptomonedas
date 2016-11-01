
<div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Redacción de correos</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
                                                    
                                                                                <?php
                                                                                if(isset($_POST['btn_save'])) {
                                                                                        $compra = protect($_POST['compra']);
                                                                                        $procesado = protect($_POST['procesado']);
                                                                                        $denegado = protect($_POST['denegado']);
                                                                                        $recibido = protect($_POST['recibido']);
                                                                                        $nuevo = protect($_POST['nuevo']);
                                                                                        $password = protect($_POST['password']);
                                                                                        $administrador = protect($_POST['administrador']);



                                                                                                $update = $db->query("UPDATE ec_emails SET compra='$compra',procesado='$procesado',denegado='$denegado',recibido='$recibido',nuevo='$nuevo',password='$password',administrador='$administrador'");
                                                                                                if ($update === TRUE) {

                                                                                                echo success("Tus cambios fueron actualizados correctamente.");
                                                                                                } else {
                                                                                                    echo error("Error al guardar los datos: " . $db->error);
                                                                                                }

                                                                                   }
                                                                                                $emailsQuery = $db->query("SELECT * FROM ec_emails ORDER BY id DESC LIMIT 1");
                                                                                                $myemails = $emailsQuery->fetch_assoc();
                                                                                ?>   


                                                                     <form action="" method="post">
                                                                        <div><h3>Al cliente cuando hace una compra</h3></div>
                                                                        <textarea name="compra" id="mytextarea1" cols="110"  rows="8" size ="50" ><?php echo $myemails['compra']; ?></textarea>


                                                                        <br>

                                                                        <div><h3>Notificación de compra procesada</h3></div>
                                                                        <textarea name="procesado" id="mytextarea2" cols="110"  rows="8" size ="50" ><?php echo $myemails['procesado']; ?></textarea>


                                                                        <br>

                                                                        <div><h3>Notificación de compra denegada</h3></div>
                                                                        <textarea name="denegado" id="mytextarea3" cols="110"  rows="8" size ="50" ><?php echo $myemails['denegado']; ?></textarea>


                                                                        <br>

                                                                        <div><h3>Notificación de pago recibido</h3></div>
                                                                        <textarea name="recibido" id="mytextarea4" cols="110"  rows="8" size ="50" ><?php echo $myemails['recibido']; ?></textarea>


                                                                        <br>

                                                                        <div><h3>Notificación de nuevo usuario</h3></div>
                                                                        <textarea name="nuevo" id="mytextarea5" cols="110"  rows="8" size ="50" ><?php echo $myemails['nuevo']; ?></textarea>


                                                                        <br>

                                                                        <div><h3>Notificación de nueva contraseña</h3></div>
                                                                        <textarea name="password" id="mytextarea6" cols="110"  rows="8" size ="50" ><?php echo $myemails['password']; ?></textarea>


                                                                        <br>

                                                                        <div><h3>Mensaje al administrador</h3></div>
                                                                        <textarea name="administrador" id="mytextarea7" cols="110"  rows="8" size ="50" ><?php echo $myemails['administrador']; ?></textarea>
                                                                        <br>

                                                                        <button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Guardar cambios</button>
                                                                     </form>

                                                        
						</div>
					</div>
				</div>
			</div>
		</div>
</div>