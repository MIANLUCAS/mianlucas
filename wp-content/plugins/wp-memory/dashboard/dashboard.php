<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-03 09:07:38
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
 global $wpmemory_memory;
    //display form
    echo '<div class="wrap-wpmemory ">' . "\n";
    echo '<h2 class="title">PHP and WordPress Memory</h2>' . "\n";
    echo '<p class="description">'.esc_attr__("This plugin check For High Memory Usage and include the result in the Tools => Site Health Page.","wpmemory");
    echo esc_attr__("This plugin also Check Memory status and allows you to increase the Php Memory Limit and WordPress Memory Limit without editing any file.","wpmemory").'</p>' . "\n";

    /////////////////


    echo '<center><h2>'.esc_attr__("Memory Usage","wpmemory").'</h2>';
    $ds = 256;
    $du = 60;
        $ds = $wpmemory_memory['wp_limit'];
        $du = $wpmemory_memory['usage'];
        if ($ds > 0)
            $perc = number_format(100 * $du / $ds, 0);
        else
            $perc = 0;
        if ($perc > 100)
            $perc = 100;
        //die($perc);
        $color = '#e87d7d';
        $color = '#029E26';
        if ($perc > 50)
            $color = '#e8cf7d';
        if ($perc > 70)
            $color = '#ace97c';
        if ($perc > 50)
            $color = '#F7D301';
        if ($perc > 70)
            $color = '#ff0000';
        $initValue = $perc;



    require_once "circle_memory.php";



    /////////////////////


    $mb = 'MB';
    echo '<br />';
    echo '<hr>';
    echo '<b>';
    echo 'WordPress Memory Limit (*): ' . $wpmemory_memory['wp_limit'] . $mb .
        '&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;';
    $perc = $wpmemory_memory['usage'] / $wpmemory_memory['wp_limit'];
    if ($perc > .7)
        echo '<span style="color:' . $wpmemory_memory['color'] . ';">';
    echo esc_attr__("Your usage now","wpmemory").': ' . $wpmemory_memory['usage'] .
        'MB &nbsp;&nbsp;&nbsp;';
    if ($perc > .7)
        echo '</span>';
    echo '|&nbsp;&nbsp;&nbsp;'.esc_attr__("Total Php Server Memory","wpmemory").' (**): ' . $wpmemory_memory['limit'] .
        'MB';
    echo '</b>';
    echo '</center>';
    echo '<hr>';
    echo '<br />';
    echo esc_attr__("The PHP memory limit needs be bigger than WordPress Memory Limit.","wpmemory");
    echo '<br />';
    echo '<br />';
    echo  '(*)'.esc_attr__("Instructions to increase WordPress Memory Limit:","wpmemory");
    echo '<a href="http://wpmemory.com/fix-low-memory-limit/">';
    echo  ' '.esc_attr__("Click Here to Tips","wpmemory");
    echo '</a>';
    echo '<br />';
    echo '<br />';
    echo '(**)'.esc_attr__('The Total Php Server Memory is the PHP "Memory Limit" usually defined on your php.ini file.','wpmemory');
    echo '<a href="http://wpmemory.com/php-memory-limit/">';
    echo  esc_attr__("Click Here to learn more","wpmemory");
    echo '</a>';
    echo '<div class="main-notice">';
    echo '</div>' . "\n";
 //   echo '</div>';
    ?>
    <br /><br />
<b>
<?php echo  esc_attr__("How to Tell if Your Site Needs a Shot of more Memory","wpmemory"); ?>
:
</b>
<br /><br />

<?php 
/*
echo  esc_attr__("If you got","wpmemory");
echo '<i> ';
 echo  esc_attr__("Fatal error: Allowed memory size of xxx bytes exhausted","wpmemory"); 
 echo '</i> ';
echo  esc_attr__("or","wpmemory"); 
echo '<br> ';
echo  esc_attr__("if your site is behaving slowly, or pages fail to load, you get random white screens of death or 500 internal server error you may need more memory. Several things consume memory, such as WordPress itself, the plugins installed, the theme you're using and the site content.","wpmemory"); 

echo '<br> ';
*/

echo  esc_attr__("If you got","wpmemory");
echo '<i> ';

 
echo  esc_attr__("Fatal error: Allowed memory size of xxx bytes exhausted","wpmemory");

echo '</i> ';
echo  esc_attr__("or","wpmemory"); 
echo '<br>';

echo  esc_attr__("if your site is behaving slowly, or pages fail to load, you get random white screens of death or 500 internal server error you may need more memory. Several things consume memory, such as WordPress itself, the plugins installed, the theme you're using and the site content.","wpmemory");

echo '<br> ';
echo  esc_attr__("Basically, the more content and features you add to your site, the bigger your memory limit has to be. if you're only running a small site with basic functions without a Page Builder and Theme Options (for example the native Twenty twenty) maybe you don’t need make memory adjustments. However, once you use a Premium WordPress theme and you start encountering unexpected issues, it may be time to adjust your memory limit to meet the standards for a modern WordPress installation.","wpmemory");

echo '<br> ';
echo  esc_attr__("Increase the WP Memory Limit is a standard practice in WordPress and you find instructions also in the official WordPress documentation (Increasing memory allocated to PHP).","wpmemory");

echo '</div>';
