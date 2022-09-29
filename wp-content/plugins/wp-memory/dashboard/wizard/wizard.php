<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 12:42:23
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
} 




global $wpmemory_memory;
global $wpmemory_checkversion;






if( isset( $_GET[ 'step' ] ) ) 
    $active_step = sanitize_text_field($_GET[ 'step' ]);
else
   $active_step = '1';


echo '<div class="wrap-wpmemory ">' . "\n";



if($active_step == '1') {

    echo '<h2 class="title">Memory Wizard Step 1/3</h2>' . "\n";

    echo '<div class="description">';

        if (!wpmemory_isShellEnabled()) {
            echo esc_attr__('We are unable to get your Hardware Memory.','wpmemory');
            echo '<br>';
            echo esc_attr__('Please, talk with your hosting company and ask to them  the amount of your server RAM (or enable shell_exec function).','wpmemory');
            // return;
            $wpmemory_total_ram = 0;
        }
        else {

            $wpmemory_total_ram = shell_exec("grep -w 'MemTotal' /proc/meminfo | grep -o -E '[0-9]+'");

            if (gettype($wpmemory_total_ram) != 'numeric')
                $wpmemory_total_ram = (int)$wpmemory_total_ram;
            
            if ($wpmemory_total_ram > 0)
                $wpmemory_total_ram =    wpmemory_format_filesize_kB($wpmemory_total_ram);
            else {
                echo esc_attr__('Unable to find your total RAM memory. Please, ask to your hosting company.','wpmemory');
                //return;
            }

        }

       // echo '<div class="wrap-wpmemory ">' . "\n";



        esc_attr_e("This Wizard can help you to configurate your Server Memory.","wpmemory");
        echo '<br />';
        esc_attr_e("(WordPress Memory Limit and PHP Memory)","wpmemory");
        echo "</p>" . "\n";

        echo '<br />';

        esc_attr_e("The Server RAM (Random Access Memory) is a 
        physical memory, which usually takes the form of cards (DIMMs) attached onto the motherboard.
        Talk with your hosting company if you need to increase that.","wpmemory");
        echo '<hr><strong>';
        echo esc_attr__('Total Current RAM Memory:',"wpmemory").' ' . $wpmemory_total_ram;

        echo '</strong><hr>';
        echo '<br />';



        esc_attr_e("This Total is the Max you can allocate at next step.",'wpmemory');
        echo '<br />';
        //echo '<br />';

        echo '<br />';

        echo '<br />';

        // http://minozzi.eu/wp-admin/tools.php?page=wp_memory_admin_page&tab=wizard
        //  href="tools.php?page=wp_memory_admin_page&tab=dashboard
        echo '<center>';
        echo '<a href="tools.php?page=wp_memory_admin_page&tab=wizard&step=2" id="themefix-wpconfig-button-xxxxxxxxxx" class="button button-primary">';
        esc_attr_e("Next Step","wpmemory");
        echo ' ></a>';
        echo '</center>';

        echo '</div>'; // Descriptionn

} // End Step 1
elseif ($active_step == '2') {  // STEP 2

    echo '<h2 class="title">Memory Wizard Step 2/2</h2>' . "\n";

    echo '<div class="description">';

    if (!function_exists('ini_set')) {
            if (is_admin()) {
                esc_attr_e("Your server doesn't have a PHP function ini_set.","wpmemory");
                echo '<br>';
                esc_attr_e("Please, talk with your hosting company.","wpmemory");
                echo '<br>';
                echo '</div>'; // Descriptionn
                return;
            }
    }



    if (isset($_GET['page']) && $_GET['page'] == 'wp_memory_admin_page') {
        if (isset($_POST['process']) && $_POST['process'] == 'wp_memory_admin_page') {
            //get limit
            if (isset($_POST['wp_memory_select'])) {
                $wp_php_memory_limit = sanitize_text_field($_POST['wp_memory_select']);
                //update options
                if (is_numeric($wp_php_memory_limit))
                    $wp_php_memory_limit = (string) $wp_php_memory_limit;
                if (!update_option('wpmemory_php_memory_limit', $wp_php_memory_limit))
                    add_option('wpmemory_php_memory_limit', $wp_php_memory_limit);
                $wpmemory_memory['limit'] = $wp_php_memory_limit;
                wpmemory_updated_message();
            }
        }
    
    }

    //display form
   // echo '<div class="wrap-wpmemory ">' . "\n";
    //echo '<h2 class="title">PHP Memory Limit</h2>' . "\n";
    //echo '<p class="description">';

    esc_attr_e("The PHP Memory Limit is the Total Php Server Memory and is usually defined on your php.ini file.","wpmemory");
    echo  "\n";

    $mb = 'MB';

    echo '<br />';
    echo '<br />';

    echo '<hr><strong>';
    echo  esc_attr__('Total Current PHP Memory Limit:','wpmemory');
    echo ' '.$wpmemory_memory['limit'] .'MB';
    echo '</strong><hr>';
    echo '<br />';
    echo esc_attr__('The PHP memory limit needs be not bigger than your Hardware Memory (Previous Step) and needs be bigger than WordPress Memory Limit (Next Step).', 'wpmemory');
    echo '<br />';
    echo '<br />';

    

    echo esc_attr__("This sets the maximum amount of memory in bytes that a script is allowed to allocate. This helps prevent poorly written scripts or any temporary fail for eating up all available memory on a server...","wpmemory");

    echo '<br />';
    echo '<br />';

    echo esc_attr__("We suggest 512 Mb.","wpmemory");


    echo '<br />';
    echo '<br />';
    



    // echo 'The Total Php Server Memory is the PHP "Memory Limit" usually defined on your php.ini file.';
    echo '<a href="http://wpmemory.com/php-memory-limit/">';
    esc_attr_e("Click Here to learn more", "wpmemory");
    echo '</a>';
    echo '<br />';
    echo '<br />';
    if ($wpmemory_memory['limit'] < 1128) {

        echo ' ';
        esc_attr_e("We can update it without touch your php.ini file. Just choose the amount below and click UPDATE.","wpmemory");

        echo '<form class="wpmemory -form" method="post" action="admin.php?page=wp_memory_admin_page&tab=wizard&step=2">' . "\n";
        echo '<input type="hidden" name="process" value="wp_memory_admin_page"/>' . "\n";
        echo '<br />' . "\n";
        $wpmeml = $wpmemory_memory['limit'];
    ?>
        <label for="wpmemorylimit"><?php esc_attr_e("Update the PHP memory limit to","wpmemory")?>:</label>
        <select name="wp_memory_select" id="wp_memory_select">
            <option value="64" <?php echo ($wpmeml == '64') ? ' selected="selected"' : ''; ?>>64 MB</option>
            <option value="96" <?php echo ($wpmeml == '96') ? ' selected="selected"' : ''; ?>>96 MB</option>
            <option value="128" <?php echo ($wpmeml == '128') ? ' selected="selected"' : ''; ?>>128 MB</option>
            <option value="256" <?php echo ($wpmeml == '256') ? ' selected="selected"' : ''; ?>>256 MB</option>
            <option value="512" <?php echo ($wpmeml == '512') ? ' selected="selected"' : ''; ?>>512 MB</option>
            <option value="1024" <?php echo ($wpmeml == '1024') ? ' selected="selected"' : ''; ?>>1024 MB</option>
        </select>
        <br />
    <?php
       // echo '<br />';
        echo '<br />';
        echo '<input class="wpmemory -submit button-primary" type="submit" value="Update" />';
        echo '</form>' . "\n";
    }
    /*
    echo '<div class="main-notice">';
    echo '</div>' . "\n";
    echo '</div>';
    */

    echo '<br />';

    echo '<br />';

    // http://minozzi.eu/wp-admin/tools.php?page=wp_memory_admin_page&tab=wizard
    //  href="tools.php?page=wp_memory_admin_page&tab=dashboard
    echo '<center>';
    echo '<a href="tools.php?page=wp_memory_admin_page&tab=wizard&step=1" id="themefix-wpconfig-button-xxxxxxxxxx" class="button button-primary"><&nbsp;';
    esc_attr_e("Previous Step","wpmemory");
    echo ' </a>';

    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

    echo '<a href="tools.php?page=wp_memory_admin_page&tab=wizard&step=3" id="themefix-wpconfig-button-xxxxxxxxxx" class="button button-primary">';
    esc_attr_e("Next Step","wpmemory");
    echo ' ></a>';
    echo '</center>';


    echo '</div>'; // Description

} // End Step 2
elseif ($active_step == '3') {  ////////////////// STEP 3

    echo '<h2 class="title">Memory Wizard Step 3/3</h2>' . "\n";

    // echo '<p class="description">';


    //display form
    // echo '<div class="wrap-wpmemory ">' . "\n";
    //  echo '<h2 class="title">WordPress Memory Limit</h2>' . "\n";
    echo '<div class="description">';
    esc_attr_e("WordPress Memory Limit is the maximum amount of memory that can be consumed by PHP.","wpmemory");
    echo '<br />';
    esc_attr_e("WP_MEMORY_LIMIT option allows you to specify that in your wp-config.php file on your root folder.","wpmemory");
    echo "</p>" . "\n";
    $mb = 'MB';
    echo '<hr>';
    esc_attr_e("Total Current WordPress Memory Limit:","wpmemory");
    echo ' ' . $wpmemory_memory['wp_limit'] . $mb;
    echo '<hr>';
    echo '<br />';
    echo '<a href="http://wpmemory.com/fix-low-memory-limit/">';
    esc_attr_e("Click Here to Learn More","wpmemory");
    echo '</a>';
    echo '<br />';
    echo '<br />';
    esc_attr_e("This Amount need be minor than PHP Memory Limit. (Previous Step)","wpmemory");
    echo '<br />';
    esc_attr_e("We can update it for you. Just select below and click FIX IT NOW.","wpmemory");
    echo ' ';
    esc_attr_e("(We will make one backup and update the file wp-config.php)","wpmemory");
    echo '<br />';
    if (empty($wpmemory_checkversion)) {
        echo '<b>';
        esc_attr_e("Free Version max memory upgrade is 128M","wpmemory");
        echo '<br />';
        esc_attr_e("Go Premium and you can setup up the limit up to 1024M. Just click the premium Tab above.","wpmemory").'</b>';
    }

    echo '<br />';
    esc_attr_e("We suggest define Maximum Memory Limit to 256 or 512MB.","wpmemory");
    esc_attr_e("This WordPress Maximum Memory is by instance.", "wpmemory");
    esc_attr_e("As you add visitors, you may find additional instances, that are all using memory.", "wpmemory");
    esc_attr_e("Each PHP instance of WP uses only as much memory as it needs.", "wpmemory");

    /*
    Each WordPress instance can use up to 1GB (which is a crazy large amount of memory to allow). 
    As you add visitors, you may find additional instances of apache (or nginx) and 
    PHP created that are all using memory. 
     Each PHP instance of WP uses only as much memory as it needs.
     https://wordpress.org/support/topic/wordpress-memory-limit-in-wp-config/
    */



    echo '<form class="wpmemory -form" method="post" action="admin.php?page=wp_memory_admin_page&tab=wpmemory">' . "\n";
    echo '<input type="hidden" name="process" value="wp_memory_admin_page"/>' . "\n";
    echo '<br />' . "\n";
    //echo '</form>' . "\n";
    $wpmeml = $wpmemory_memory['wp_limit'];
    ?>
    <label for="wpmemorylimit"><?php esc_attr_e("Update the WordPress Memory Limit to","wpmemory");?>:</label>
    <select name="wp_memory_select" id="wp_memory_select">
        <option value="64" <?php echo ($wpmeml == '64') ? ' selected="selected"' : ''; ?>>64 MB</option>
        <option value="96" <?php echo ($wpmeml == '96') ? ' selected="selected"' : ''; ?>>96 MB</option>
        <option value="128" <?php echo ($wpmeml == '128') ? ' selected="selected"' : ''; ?>>128 MB</option>
        <?php
        if (!empty($wpmemory_checkversion)) { ?>
            <option value="256" <?php echo ($wpmeml == '256') ? ' selected="selected"' : ''; ?>>256 MB</option>
            <option value="512" <?php echo ($wpmeml == '512') ? ' selected="selected"' : ''; ?>>512 MB</option>
            <option value="1024" <?php echo ($wpmeml == '1024') ? ' selected="selected"' : ''; ?>>1024 MB</option>
        <?php } ?>
    </select>
    <br />
    <?php

    // echo '<br />';
    echo '<p style="color:red;">';
    esc_attr_e("Next window, before proceed, copy the link location to restore your wp-config.php",'wpmemory');
    echo '<br />';
    esc_attr_e('Some hosting company, to "protect you", can damage the file wp-config.php','wpmemory');
    echo '</p>';
    //echo '<br />';

    echo '<br />';

    echo '<a href="#" id="themefix-wpconfig-button" class="button button-primary">Fix it Now!</a>';
    echo '</form>' . "\n";
    //echo '<div class="main-notice">';
    //echo '</div>' . "\n";


    echo '<center>';
    echo '<a href="tools.php?page=wp_memory_admin_page&tab=wizard&step=2" id="themefix-wpconfig-button-xxxxxxxxxx" class="button button-primary">< &nbsp;';
    esc_attr_e("Previous Step","wpmemory");
    echo '</a>';
    echo '</center>';


    echo '</div>';


    $verticalmenu_urlkey = urlencode(substr(NONCE_KEY, 0, 10));
    $verticalmenu_mypath = WPMEMORYURL . 'dashboard/fixconfig/fixconfig.php';
    //$verticalmenu_myrestore = WPMEMORYURL . 'public/restore-config.php?key=' . $verticalmenu_urlkey;
    $verticalmenu_myrestore = WPMEMORYURL . 'public/restore-config.php';
    $verticalmenu_email = get_bloginfo('admin_email');
    global $wpmemory_memory;
    ?>
    <!-- ///////////// Fix Config /////////////////  -->
    <div id="themefix-wpconfig" style="display: none;">
        <div class="themefix-message-wrap" style="">
            <div class="themefix-message" style="">
                <?php esc_attr_e("If your server allow us, we can try to fix your file wp-config.php to release more memory.","wpmemory");?>
                <br />
                <p style="color:red;">
                <strong><?php esc_attr_e("Please, copy the url blue below to safe place before to proceed.","wpmemory");?></strong>
                <br />
                </p>
                <?php esc_attr_e("Use the url only to undo this operation if you've problem accessing your site after the update.","wpmemory");?>
                <br />
                <br />
                <?php esc_attr_e("After Copy the URL, click UPDATE to proceed or Cancel to abort.","wpmemory");?>
                <br /> <br />
                <textarea rows="3" id="restore_wpconfig" name="restore_wpconfig" style="width:100%; color: blue;"><?php echo $verticalmenu_myrestore; ?></textarea>
                <textarea rows="6" id="feedback_wpconfig" name="feedback_wpconfig" style="width:100%; font-weight: bold;"></textarea>
                <br /><br />
                <img alt="aux" src="/wp-admin/images/wpspin_light-2x.gif" id="wpmemory-imagewait20" />
                <input type="hidden" id="email" name="email" value="<?php echo $verticalmenu_email; ?>" />
                <input type="hidden" id="url_config" name="url_config" value="<?php echo $verticalmenu_mypath; ?>" />
                <input type="hidden" id="verticalmenu_urlkey" name="server_memory" value="<?php echo $verticalmenu_urlkey; ?>" />
                <input type="hidden" id="server_memory" name="server_memory" value="<?php echo (int) ini_get('memory_limit') ?>" />
                <a href="#" id="button-close-wpconfig" class="button button-primary button-close-wpconfig"><?php _e("Update", "wpmemory"); ?></a>
                <a href="#" id="button-cancell-wpconfig" class="button button-primary button-cancell-wpconfig"><?php _e("Cancel", "wpmemory"); ?></a>
                <br /><br />
            </div>
        </div>
    </div>
    <!-- ///////////// End Fix config /////////////////  -->

    <?php




} // end last Step (3)    


