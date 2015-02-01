<?php
/*
Plugin Name: Simple Flickr Widget
Plugin URI: https://wordpress.org/plugins/simple-flickr-widget/
Description: A widget which will display your latest Flickr photos. 
Version: 1.0.1
Author: Sayful Islam
Author URI: http://www.sayful.net
License: GPL2
*/
class SIS_Flickr extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'flickr_widget', // Base ID
            __( 'Simple Flickr Widget', 'sfwidget' ), // Name
            array( 'description' => __( 'Show your favorite Flickr photos.', 'sfwidget' ), ) // Args
        );

        // Register site styles and scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'sis_flickr_plugin_scripts' ) );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */

    public function widget( $args, $instance ) {
        $title                      = apply_filters( 'widget_title', $instance['title'] );
        $flickr_id                  = $instance['flickr_id'];
        $number                     = $instance['number'];
        $row_number                 = $instance['row_number'];
     
        echo $args['before_widget'];
     
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
     
        ?>
        <ul id="flickrcbox" class="simple_flicker_widget row col-<?php echo $row_number; ?>"></ul>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                
                jQuery('#flickrcbox').jflickrfeed({
                    limit: <?php echo $number; ?>,
                    qstrings: {
                        id: '<?php echo $flickr_id; ?>'
                    },
                    itemTemplate: '<li><a target="_blank" href="{{image_b}}"><img src="{{image_s}}" alt="{{title}}" /></a></li>'
                });

            });
        </script>
        <?php
     
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Flickr Photos', 'sfwidget' );
        $flickr_id = ! empty( $instance['flickr_id'] ) ? $instance['flickr_id'] : '';
        $number = ! empty( $instance['number'] ) ? $instance['number'] : '6';
        $row_number = ! empty( $instance['row_number'] ) ? $instance['row_number'] : '2';
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('flickr_id'); ?>">Flickr User ID:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo $flickr_id; ?>">
                <small>Dont't know your ID. Head on over to <a target="_blank" href="http://idgettr.com/">idGettr</a> to find it.</small>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>">Number of Photos:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" value="<?php echo $number; ?>" min="1" max="20">
                <small>Set how many photos you want to show.  Flickr seems to limit its feeds to 20. So you can use maximum 20 photos.</small>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('row_number'); ?>">Number of Photos per row:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('row_number'); ?>" name="<?php echo $this->get_field_name('row_number'); ?>" type="number" value="<?php echo $row_number; ?>" max="6" min="1">
                <small>Set how many photos you want to show in a row. So you can use minimum 1 photo and maximum 6 photos.</small>
            </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['flickr_id'] = ( ! empty( $new_instance['flickr_id'] ) ) ? strip_tags( $new_instance['flickr_id'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
        $instance['row_number'] = ( ! empty( $new_instance['row_number'] ) ) ? strip_tags( $new_instance['row_number'] ) : '';

        return $instance;
    }
    /**
     * Registers and enqueues widget-specific styles.
     */
    public function sis_flickr_plugin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('sis_flickrfeed_script',plugins_url( '/js/jflickrfeed.min.js' , __FILE__ ),array( 'jquery' ));
        wp_enqueue_style('sis_main_style',plugins_url( '/css/style.css' , __FILE__ ));
    } // end sis_flickr_plugin_scripts

} // class SIS_Flickr

// register SIS_Flickr widget
add_action( 'widgets_init', create_function( '', 'register_widget("SIS_Flickr");' ) );