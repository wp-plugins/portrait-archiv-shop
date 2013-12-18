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
 
 function pawps_getTemplatePath($templateName) {
	return plugin_dir_path( __FILE__ ) . '/templates/' . get_option(PAWPS_TEMPLATE_NAME) . '/' . $templateName . '.tpl';
 }

?>