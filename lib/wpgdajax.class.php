<?php
/**
 * WPGDAjax Class
 * 
 * @package  WPGlassdoor
 * @since  1.0
 */
class WPGDAjax
{
    /**
     * Initialize WPGlassdoor AJAX functionality.
     * @return void
     */
    static public function init()
    {
        /** Add various AJAX actions and filters. */
        add_action(
            'wp_ajax_wpgd_dismiss_message',
            array('WPGDAjax', 'wpgd_dismiss_message')
        );

    }


    static public function wpgd_dismiss_message()
    {
        if (isset($_REQUEST['msg_slug']) and ($_REQUEST['msg_slug'] != '')) {

            update_option('wpgd_hide_notice_' . $_REQUEST['msg_slug'], true);

            echo json_encode('ok');
            exit();
        }
    }

}