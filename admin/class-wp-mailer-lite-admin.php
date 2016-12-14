<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://stephenafamo.com
 * @since      1.0.0
 *
 * @package    Wp_Mailer_Lite
 * @subpackage Wp_Mailer_Lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Mailer_Lite
 * @subpackage Wp_Mailer_Lite/admin
 * @author     Stephen Afam-Osemene <stephenafamo@gmail.com>
 */
class Wp_Mailer_Lite_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Mailer_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mailer_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-mailer-lite-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Mailer_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mailer_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-mailer-lite-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	 
	public function add_plugin_admin_menu() {

	    /*
	     * Add a settings page for this plugin to the Settings menu.
	     *
	     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
	     *
	     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
	     *
	     */
	    add_options_page( 'WP Mailer Lite', 'WP Mailer Lite', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
	    );
	}

	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	 
	public function add_action_links( $links ) {
	    /*
	    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	    */
	   $settings_link = array(
	    '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	 
	public function display_plugin_setup_page() {
	    include_once( 'partials/wp-mailer-lite-admin-display.php' );
	}


	 public function options_update() {
	    register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	 }

	public function validate($input) {
	    // All checkboxes inputs        
	    $valid = array();

	    //Cleanup
	    $valid['api_key'] = filter_var($input['api_key'], FILTER_SANITIZE_STRING);
	    $valid['default_group_id'] = filter_var($input['default_group_id'], FILTER_SANITIZE_STRING);
	    
	    return $valid;
	 }

	 public function add_new_user($user_id){

		$options = get_option($this->plugin_name);
	 	$mailer_lite = (new \MailerLiteApi\MailerLite($options['api_key']))->groups();

		$groupId = $options['default_group_id'];

		$user = get_userdata($user_id);

		$subscriber = [
		  'email' => $user->user_email,
		  'fields' => [
		    'name' => $user->first_name,
		    'last_name' => $user->last_name
		  ]
		];

		$addedSubscriber = $mailer_lite->addSubscriber($groupId, $subscriber);

		update_user_meta( $user_id, 'mailer_lite_subscriber_id', $addedSubscriber->id);


	 }

	 public function update_user($user_id, $old_user){

	 	$user = get_userdata($user_id);

	 	if ($user->mailer_lite_subscriber_id){

			$options = get_option($this->plugin_name);
		 	$mailer_lite = (new \MailerLiteApi\MailerLite($options['api_key']))->subscribers();

		 	if ($user->user_email == $old_user->user_email){

				$subscriberData = [
				  'fields' => [
				    'name' => $user->first_name,
				    'last_name' => $user->last_name
				  ]
				];

				$mailer_lite->update($user->mailer_lite_subscriber_id, $subscriberData);

			} else {

				$subscriberData = [
				  'type' => 'unsubscribed',
				];

				$mailer_lite->update($user->mailer_lite_subscriber_id, $subscriberData);

				$this->add_new_user($user_id);
			}

	 	} else {	 		
	 		$this->add_new_user($user_id);
		}

	 }

	 public function delete_user($user_id){

	 	$user = get_userdata($user_id);

	 	if ($user->mailer_lite_subscriber_id){

			$options = get_option($this->plugin_name);
		 	$mailer_lite = (new \MailerLiteApi\MailerLite($options['api_key']))->subscribers();

			$subscriberData = [
			  'type' => 'unsubscribed',
			];

			$mailer_lite->update($user->mailer_lite_subscriber_id, $subscriberData);
		}

	 }

	 public function sync_all (){
	 	$users = get_users( [ 'fields' => 'ID' ] );
	 	$user_index = 0;
	 	$total_users = count($users);

	 	wp_schedule_single_event( time(), 'sync_batch_of_10', array( $users, $user_index, $total_users) );
	 	wp_schedule_single_event( time()+(60*60*24), 'sync_control_value');
        wp_redirect(admin_url( 'options-general.php?page='.$this->plugin_name )); exit;
	 }

	 public function sync_batch_of_10 ($users, $user_index, $total_users) {

	 	$count = 0;        
        
        while ($user_index<$total_users && $count<10){ 

        	$this->add_new_user($users[$user_index]);

        	$user_index++;
	        $count++;
	    }
        if($user_index<$total_users){
	        wp_schedule_single_event( time(), 'sync_batch_of_10', array( $csv, $student_number, $student_total_number) );
        } else {
        	wp_clear_scheduled_hook('sync_control_value');
        }
	 }

}
