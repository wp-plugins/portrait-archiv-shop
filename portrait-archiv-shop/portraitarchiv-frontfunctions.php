<?php
 
 function pawps_displayError($error = null, $message = null) {
 	if (isset($error) && (strlen($error) > 0)) {
		?>
		<div style="background-color: rgb(255, 102, 51); color: rgb(56, 56, 56); font-weight: bold; padding: 5px; margin-right: 10px; margin-left: 10px;"><?php echo $error; ?></div>
		<?php
	} 
	
	if (isset($message) && (strlen($message) > 0)) {
		?>
		<div style="background-color: rgb(180, 205, 205); color: rgb(0, 0, 0); font-weight: bold; padding: 5px; margin-right: 10px; margin-left: 10px;"><?php echo $message; ?></div>
		<?php 
	}
	 
 } 
 
 function pawps_getTemplatePath($fileName) {
 	$userTemplate = get_option(PAWPS_TEMPLATE_NAME);
	if (strpos($userTemplate, PAWPS_USERTPL_START) > -1) {
		// Benutzertemplate
		return pawps_getUsertemplateDir() . $userTemplate . "/" . $fileName . ".tpl"; 
	} else {
		return pawps_getTemplateFilePath($fileName . '.tpl');
	}
 }
 
 function pawps_getTemplateFilePath($fileName) {
	return plugin_dir_path( __FILE__ ) . 'templates/' . get_option(PAWPS_TEMPLATE_NAME) . '/' . $fileName;
 }
 
 function pawps_getUsertemplateDir() {
 	$upload_dir = wp_upload_dir();
 	return $upload_dir['basedir'] . "/pawps_usertemplates/";
 }
 
 function pawps_getUsertemplateDirUrl() {
 	$upload_dir = wp_upload_dir();
 	return $upload_dir['baseurl'] . "/pawps_usertemplates/";
 }

?>