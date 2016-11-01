 <!-- Footer -->
    <footer class="text-center mi-div-abajo">
        <div class="cover">
            <div class="container">
                <div>
                    <div class="footer-col col-md-4 text-left">
                        <h3><?php echo $lang['pages']; ?></h3>
                        <ul class="list-unstyled">
							<li><a href="<?php echo $settings['url']; ?>pages/faq"><?php echo $lang['faq']; ?></a></li>
							<li><a href="<?php echo $settings['url']; ?>page/terminos-de-servicio"><?php echo $lang['terms_of_service']; ?></a></li>
							<li><a href="<?php echo $settings['url']; ?>page/privacy-policy"><?php echo $lang['privacy_policy']; ?></a></li>
							<li><a href="<?php echo $settings['url']; ?>page/about"><?php echo $lang['about']; ?></a></li>
							<li><a href="<?php echo $settings['url']; ?>pages/contact-us"><?php echo $lang['contact_us']; ?></a></li>
						</ul>
                    </div>
                    <div class="footer-col col-md-4 text-left">
                        <h3><?php echo $lang['follow_us']; ?></h3>
                        <ul class="list-inline">
                            <li>
                                <a href="<?php echo $settings['fb_link']; ?>" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="<?php echo $settings['gp_link']; ?>" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a>
                            </li>
                            <li>
                                <a href="<?php echo $settings['tw_link']; ?>" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="<?php echo $settings['li_link']; ?>" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a>
                            </li>
                            <li>
                                <a href="<?php echo $settings['dr_link']; ?>" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-dribbble"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-col col-md-4 text-left">
                        <h3><?php echo $lang['languages']; ?></h3>
                        <p><?php echo getLanguage($settings['url'],null,1); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-below">
            <div class="container">
                <div >
                    <div class="col-lg-12">
                        Copyright &copy; 2016 by <a >InterBitcoin Solutions Inc.</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll visible-xs visible-sm">
        <a class="btn btn-primary" href="#page-top">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

 <!-- jQuery -->


    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $settings['url']; ?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo $settings['url']; ?>assets/js/functions.js"></script>
	<!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="<?php echo $settings['url']; ?>assets/js/classie.js"></script>
    <script src="<?php echo $settings['url']; ?>assets/js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="<?php echo $settings['url']; ?>assets/js/jqBootstrapValidation.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo $settings['url']; ?>assets/js/freelancer.js"></script>
	<input type="hidden" id="url" value="<?php echo $settings['url']; ?>">
</body>

</html>
