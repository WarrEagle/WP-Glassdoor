<?php
/**
 * WP Glassdoor Widget
 *
 * @package  WPGlassdoor
 * @since  1.0
 */

class WPGDWidget extends WP_Widget
{
    /**
     * Register widget with WordPress
     */
    public function __construct()
    {
        parent::__construct(
            'wpgd_widget',
            'WP Glassdoor Widget',
            array(
                'description' => __("Glassdoor \"Job Title Salary Widget\".", 'text_domain'),
            )
        );
    }

    /**
     * Front-end display of widget.
     * @param  array $args     Widget arguments
     * @param  array $instance Saved values from DB.
     * @return void
     */
    public function widget($args, $instance)
    {
        global $post;

        extract($args);

        $settings = WPGlassdoor::get_settings();
        $settings = WPGDUtils::parse_args_dbl($instance, $settings);
        $title = apply_filters('widget_title', $settings['title']);

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        // For testing
        // $post->post_title = 'Project Management';

        $format = $settings['widget_width'] . "x" . $settings['widget_height'];
        if ($settings['widget_chart']) {
            $format .= "-chart";
        }

        ?>
        <div class="gdWidget" style="margin-top:7px;"><?php echo $post->post_title; ?> jobs on <a 
href="http://www.glassdoor.com/api/api.htm?version=1&action=salaries&t.s=wm&t.a=c&t.p=<?php echo $settings['tracking_id']; ?>&format=<?php echo $format; ?>&jobTitle=<?php echo rawurlencode($post->post_title); ?>&location=" target="_gd">Glassdoor.com</a> | 
More details for <a href="http://www.glassdoor.com/api/api.htm?version=1&action=jobs&t.s=wm&t.a=c&t.p=<?php echo $settings['tracking_id']; ?>&jobTitle=<?php echo rawurlencode($post->post_title); ?>" target="_gd"><?php echo $post->post_title; ?></a> jobs | <a 
href="http://www.glassdoor.com/Salaries/index.htm?t.s=w-m&t.a=c&t.p=<?php echo $settings['tracking_id']; ?>" target="_gd">More 
salaries</a></div><script src="http://www.glassdoor.com/static/js/api/widget/v1.js" type="text/javascript"></script>
        <?php

        echo $after_widget;
    }


    /**
     * Back-end widget form.
     * @param  array $instance Previously saved values from database.
     * @return void
     */
    public function form($instance)
    {
        $settings = WPGlassdoor::get_settings();
        $settings = WPGDUtils::parse_args_dbl($instance, $settings);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tracking_id' ); ?>"><?php _e( 'Tracking ID' ); ?></label> 
            <input class="" id="<?php echo $this->get_field_id( 'tracking_id' ); ?>" name="<?php echo $this->get_field_name( 'tracking_id' ); ?>" type="text" value="<?php echo $settings['tracking_id']; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'widget_width' ); ?>"><?php _e( 'Widget width [pixels]' ); ?></label> 
            <input class="" id="<?php echo $this->get_field_id( 'widget_width' ); ?>" name="<?php echo $this->get_field_name( 'widget_width' ); ?>" type="text" value="<?php echo $settings['widget_width']; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'widget_height' ); ?>"><?php _e( 'Widget height [pixels]' ); ?></label> 
            <input class="" id="<?php echo $this->get_field_id( 'widget_height' ); ?>" name="<?php echo $this->get_field_name( 'widget_height' ); ?>" type="text" value="<?php echo $settings['widget_height']; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'widget_chart' ); ?>"><?php _e( 'Widget Chart:' ); ?></label> 
            <input class="" id="<?php echo $this->get_field_id( 'widget_chart' ); ?>" name="<?php echo $this->get_field_name( 'widget_chart' ); ?>" type="radio" <?php echo $settings['widget_chart'] ? 'checked="checked"' : '' ?> value='1' />&nbsp;Yes&nbsp;&nbsp;<input class="" id="<?php echo $this->get_field_id( 'widget_chart' ); ?>" name="<?php echo $this->get_field_name( 'widget_chart' ); ?>" type="radio" <?php echo !$settings['widget_chart'] ? 'checked="checked"' : '' ?> value='0' />&nbsp;No
        </p>

        <?php
    }


    /**
     * Sanitize widget form values as they are saved.
     * @param  array $new_instance Values just sent to be saved.
     * @param  array $old_instance Previously saved values from DB.
     * @return array               Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = WPGDUtils::parse_args_dbl($new_instance, $old_instance);

        return $instance;
    }

}

?>