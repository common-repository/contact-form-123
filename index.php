<?php
/*
Plugin Name: Contact Form 123
Plugin URI: https://wordpress.org/plugins/Contact-Form-123/
Description: simple contact form for wordpress
Version: 1.9
Author: Chugaev Aleksandr Aleksandrovich
Author URI: https://profiles.wordpress.org/aleksandrposs/
*/

function contact_form_123_add_plugin_stylesheet()  {
   wp_enqueue_style( 'style1', plugins_url( '/style.css', __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'contact_form_123_add_plugin_stylesheet');

function contact_form_123_install() {  // install plugin
  global $wpdb;
   // create table "settings"
   $table = $wpdb->prefix . "plugin_contact_form_123_settings";
    if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {	
   $sql = "CREATE TABLE `" . $table . "` (
     `contact_form_123_settings_id` int(9) NOT NULL AUTO_INCREMENT,
     `contact_form_123_settings_color` VARCHAR(15) NOT NULL,
     UNIQUE KEY `id` (contact_form_123_settings_id)
   ) DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;";
     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
     dbDelta($sql);
 }

 
}
register_activation_hook( __FILE__,'contact_form_123_install');

function contact_form_123_uninstall() { // uninstall plugin
 global $wpdb; 
  $table = $wpdb->prefix . "plugin_contact_form_123_settings	";	
 $wpdb->query("DROP TABLE IF EXISTS $table");
}
register_deactivation_hook( __FILE__,'contact_form_123_uninstall');

function contact_form_123_wpb_widgets_init() {
 
    register_sidebar( array(
        'name'          => 'Custom Header Widget Area',
        'id'            => 'custom-header-widget',
        'before_widget' => '<div class="chw-widget">adasddfgdgd',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="chw-title">adadad',
        'after_title'   => '</h2>',
    ) );
 
}
add_action( 'widgets_init', 'contact_form_123_wpb_widgets_init' );

function contact_form_123_html() {
    if ($_GET['page']=="contact") {
?>
    <center>
  <div class="messagepop pop">  
          <?php
  $error = array();
    if (isset($_POST['form_msg_email']) && $_POST['form_msg_email'] == "") {
        echo '<p style="border:0px solid black; line-height:20px;margin-bottom:-35px;">You need write email</p><br>';
        $error[] = "You need write email";
   }
  if (isset($_POST['form_msg_email']) && $_POST['form_msg_email'] != "") {
     if (!sanitize_email($_POST['form_msg_email'])){
        echo esc_html($_POST['form_msg_email'] . " - is not a valid email address");
                 $error[] = " this is not a valid email address";
     }
  }
  
   if (isset($_POST['form_msg_body'])  && $_POST['form_msg_body'] == "") {
       echo '<p style="border:0px solid black; line-height:20px;">You need write message</p>';
       $error[] = "You need write message";
  }
  
  if (isset($_POST['form_msg_email']) && isset($_POST['form_msg_body']) && count($error) == 0) {
    
    $to = get_option('admin_email');
    $subject = 'from your wordpress site';
    $body = 'From email:' . sanitize_email($_POST['form_msg_email']) .  ' <br>Message:' . sanitize_text_field($_POST['form_msg_body']);
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail( $to, $subject, $body, $headers );
    echo "Your email was sent to admin";
  }
  
  global $wpdb;
  
     $sql = "
     SELECT MAX(contact_form_123_settings_id)
     FROM " . $wpdb->prefix . "plugin_contact_form_123_settings
     ";  
  $result_last_id = $wpdb->query($sql);
  
  $sql = "
     SELECT *
     FROM " . $wpdb->prefix . "plugin_contact_form_123_settings
      WHERE contact_form_123_settings_id = " . $result_last_id . "
     ";  
   $color_scheme = $wpdb->get_results($sql);
   $color_scheme = $color_scheme[0]->contact_form_123_settings_color;
   
  ?>
      
  <form method="post" id="new_message" action="/?page=contact">
  <?php
  

  
     if ($color_scheme == "black") {   
   ?>
 
    <p><label for="email">Your email</label><br><input type="text" name="form_msg_email" id="form_email_black" /></p>
    <p><label for="body">Message</label><br><textarea name="form_msg_body" id="form_body_black" cols="35"></textarea></p>

    <p><input type="submit" value="Send Message" name="commit" id="message_submit_black"/>
  <?php
  } else  if ($color_scheme == "green") {   
   ?>
 
    <p><label for="email">Your email</label><br><input type="text" name="form_msg_email" id="form_email_green" /></p>
    <p><label for="body">Message</label><br><textarea name="form_msg_body" id="form_body_green" cols="35"></textarea></p>

    <p><input type="submit" value="Send Message" name="commit" id="message_submit_green"/>
  <?php
  } else  if ($color_scheme == "blue") {   
   ?>
 
    <p><label for="email">Your email</label><br><input type="text" name="form_msg_email" id="form_email_blue" /></p>
    <p><label for="body">Message</label><br><textarea name="form_msg_body" id="form_body_blue" cols="35"></textarea></p>

    <p><input type="submit" value="Send Message" name="commit" id="message_submit_blue"/>
  <?php
  }  else  if ($color_scheme == "red") {   
   ?>
 
    <p><label for="email">Your email</label><br><input type="text" name="form_msg_email" id="form_email_red" /></p>
    <p><label for="body">Message</label><br><textarea name="form_msg_body" id="form_body_red" cols="35"></textarea></p>

    <p><input type="submit" value="Send Message" name="commit" id="message_submit_red"/>
  <?php
  }  else  if ($color_scheme == "yellow") {   
   ?>
 
    <p><label for="email">Your email</label><br><input type="text" name="form_msg_email" id="form_email_yellow" /></p>
    <p><label for="body">Message</label><br><textarea name="form_msg_body" id="form_body_yellow" cols="35"></textarea></p>

    <p><input type="submit" value="Send Message" name="commit" id="message_submit_yellow"/>
  <?php
  } else if ($color_scheme == NULL) {   
   ?>
 
    <p><label for="email">Your email</label><br><input type="text" name="form_msg_email" id="form_email_black" /></p>
    <p><label for="body">Message</label><br><textarea name="form_msg_body" id="form_body_black" cols="35"></textarea></p>

    <p><input type="submit" value="Send Message" name="commit" id="message_submit_black"/>
  <?php
  } else if ($color_scheme == "orange") {   
   ?>
    <p><label for="email">Your email</label><br><input type="text" name="form_msg_email" id="form_email_orange" /></p>
    <p><label for="body">Message</label><br><textarea name="form_msg_body" id="form_body_orange" cols="35"></textarea></p>
    <p><input type="submit" value="Send Message" name="commit" id="message_submit_orange"/>
  <?php
  }  else if ($color_scheme == "gray") {   
   ?>
    <p><label for="email">Your email</label><br><input type="text" name="form_msg_email" id="form_email_gray" /></p>
    <p><label for="body">Message</label><br><textarea name="form_msg_body" id="form_body_gray" cols="35"></textarea></p>
    <p><input type="submit" value="Send Message" name="commit" id="message_submit_gray"/>
  <?php
  } 
  ?>
    <center>
        For hide this form press next : <a id="close" href="/">Cancel</a>
    </center>
    </p>
  </form>
  </div>
   </center>
   <?php
   }  
}
add_action('init','contact_form_123_html');

/* admin page */

function contact_form_123_register_admin_page(){
	add_menu_page( 'Contact Form 123 ', 'Contact Form 123 ', 'manage_options', 'contact_form_123', 'contact_form_123_admin_page', plugins_url( 'images/icon.png' ) ); 
}




function contact_form_123_admin_page(){  
  ?>	
	<h2>Contact Form 123 - Admin page</h2>
  
  <?php
    global $wpdb;
    
			$settings = $wpdb->get_results( "SELECT * FROM `" . $wpdb->prefix . "plugin_contact_form_123_settings` " );
  $settings = $settings[0];

        
  if ($settings == NULL) {
    
      $wpdb->insert($wpdb->prefix."plugin_contact_form_123_settings",
                    array("contact_form_123_settings_color" => "green"
                          ,
		  )
  );
      

  }
  
    // get color scheme
  ?>
  
  <form method="POST" action="/wp-admin/admin.php?page=contact_form_123">
  <b>Color scheme:</b><Br>
  <input type="radio" name="answer_color" value="black">Black<Br>
  <input type="radio" name="answer_color" value="green">Green<Br>
  <input type="radio" name="answer_color" value="blue">Blue<Br>
  <input type="radio" name="answer_color" value="red">Red<Br>
  <input type="radio" name="answer_color" value="yellow">Yellow<Br>
  <input type="radio" name="answer_color" value="orange">Orange<Br>
  <input type="radio" name="answer_color" value="gray">Gray<Br>
  <input style="cursor:pointer;" type="submit" value="change">
  </form>
   
   <br>
  
  
  <?php
  
 
  
  
   $sql = "
     SELECT MAX(contact_form_123_settings_id)
     FROM " . $wpdb->prefix . "plugin_contact_form_123_settings
     ";
  
   $result_last_id = $wpdb->query($sql);
      
  echo "Current color scheme for Contact Form 123 is <b/> ";
  if (isset($_POST['answer_color'])) {
   
            
            global $wpdb;
    
   
    $sql = "
      UPDATE " . $wpdb->prefix . "plugin_contact_form_123_settings
      SET contact_form_123_settings_color = '" . sanitize_text_field($_POST['answer_color']) . "'  
      WHERE contact_form_123_settings_id = " . $result_last_id . "
      ;";
            
      $wpdb->query($sql);
  
      echo esc_html($_POST['answer_color']);
  } else {
  
         echo $settings->contact_form_123_settings_color;
  }
  echo "</b>;";
  ?>
   <br>
   <br>
     and for full setup this plugin you need add link to wordpress menu on site...<br>
  link to <b>/?page=contact </b> <br><br>
  all messages from this form sent to admin email
   <?php
}
add_action( 'admin_menu', 'contact_form_123_register_admin_page' );
