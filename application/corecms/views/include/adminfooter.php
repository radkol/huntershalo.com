    <!-- Do not display footer in login screen page-->
    <?php if (AuthorizationService::instance()->isAdminLoggedIn()): ?>
      <!-- closing page content div and row div -->
                 </div>
            </div>
        </div>
        <!--
        <footer>
             <div class="container">
                <div class="copy text-center">
                   Copyright &COPY; Raddy CMS <?php echo date("Y"); ?>
                </div>
             </div>
        </footer>
        -->
    <?php endif; ?>
    <script src="<?php echo adminResourcePath("jquery.js","js"); ?>"></script>
    <script src="<?php echo adminResourcePath("jquery-ui.js","js"); ?>"></script>
    <script src="<?php echo adminResourcePath("bootstrap.js","js"); ?>"></script>
    <script src="<?php echo adminResourcePath("moment.js","js"); ?>"></script>
    <script src="<?php echo adminResourcePath("bootstrap-select.js","js"); ?>"></script>
    <script src="<?php echo adminResourcePath("bootstrap-datepicker.js","js"); ?>"></script>
    <script src="<?php echo adminResourcePath("bootstrap-datetimepicker.js","js"); ?>"></script>
    <script src="<?php echo adminResourcePath("tinymce/tinymce.min.js","js"); ?>"></script>
    <script src="<?php echo adminResourcePath("cms.js","js"); ?>"></script>
  </body>
</html>