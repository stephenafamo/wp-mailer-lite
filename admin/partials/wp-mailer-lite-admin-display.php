<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://stephenafamo.com
 * @since      1.0.0
 *
 * @package    Wp_Mailer_Lite
 * @subpackage Wp_Mailer_Lite/admin/partials
 */


//Grab all options
$options = get_option($this->plugin_name);

// Cleanup
$api_key = $options['api_key'];
$default_group_id = $options['default_group_id'];
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    
    <form method="post" name="mailer_lite_options" action="options.php">

    <?php settings_fields($this->plugin_name); ?>
    
        <!-- remove some meta and generators from the <head> -->
        <fieldset>
            <legend class="screen-reader-text"><span>API key</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-api_key">
                <input type="text" id="<?php echo $this->plugin_name; ?>-api_key" name="<?php echo $this->plugin_name; ?>[api_key]" value="<?= $api_key ?>"/>
                <span><?php esc_attr_e('API Key', $this->plugin_name); ?></span>
            </label>
        </fieldset>

        <!-- the group id that we want to automatically add subscribers to -->
        <fieldset>
            <legend class="screen-reader-text"><span>Group ID</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-default_group_id">
                <input type="number" id="<?php echo $this->plugin_name; ?>-default_group_id" name="<?php echo $this->plugin_name; ?>[default_group_id]" value="<?= $default_group_id ?>"/>
                <span><?php esc_attr_e('Group ID', $this->plugin_name); ?></span>
            </label>
        </fieldset>

        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>

    </form>
<?php if (!empty($api_key) && !empty($default_group_id)) :?>

    <h2> Things to note</h2>
    <ol>
        <li> Any new users will be automatically added to the group. </li>
        <li> User details will be updated automatically. </li>
        <li> If a user changes his/her email, the old email will be unsubscribed and the new email will be subscribed. </li>
        <li> Once a user is deleted, he/she will be unsubscribed. </li>
    </ol>
    <p> Use this to add all your existing users to the specified group. Best to use immediately this plugin is activated. </p>

<?php if ( !wp_next_scheduled('sync_control_value') ) :?>

    <form method="post" action="<?= esc_url( admin_url('admin-post.php') ); ?>" onsubmit="mailer_lite_sync_all.disabled=true; return true;">
        <input type="hidden" name="action" value="mailer_lite_sync_all">
        <button class="button-primary" id="mailer_lite_sync_all">Sync All</button>
    </form>

<?php ; else  :?>

    <h3> Your users are being synchronised. Refresh this page after some time. </h3>

<?php endif; endif; ?>
    
<!-- <?php ;?> -->
</div>
