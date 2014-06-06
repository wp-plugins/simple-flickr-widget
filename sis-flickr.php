<?php
/*
Plugin Name: Simple Flickr Widget
Description: A widget which will display your latest Flickr photos. 
Author: Sayful Islam
Version: 1.0
*/
function sis_flickr_plugin_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('sis_flickrfeed_script',plugins_url( '/js/jflickrfeed.min.js' , __FILE__ ),array( 'jquery' ));
    wp_enqueue_style('sis_main_style',plugins_url( '/css/style.css' , __FILE__ ));
}
add_action('widgets_init', 'sis_flickr_plugin_scripts');

class SIS_Flickr extends WP_Widget {
    
    //Constructor.....

    function SIS_Flickr() {     
        $widget_ops = array( 'classname' => 'flickr_widget', 'description' => 'Show your favorite Flickr photos.' );
        $this->WP_Widget( 'flickr_widget', 'Flickr Posts', $widget_ops);
    }

    // Displays HTML on the front end

    function widget($args, $instance) {
        extract($args);
 
        $title = apply_filters('widget_title', $instance['title']);
        $flickr_id = $instance['flickr_id'];
        $number = absint( $instance['number'] );

        if($title){
            echo $before_title;
            echo $title;
            echo $after_title;
        }

        ?>
        <ul id="flickrcbox" class="sis_flickr"></ul>
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
        
    }
 
    //Creating The Form

    function form($instance) {
        $title = esc_attr($instance['title']);
        $flickr_id = $instance['flickr_id'];
        $number = absint($instance['number']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('flickr_id'); ?>">Flickr User ID:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo $flickr_id; ?>" />
                <small>Dont't know your ID. Head on over to <a target="_blank" href="http://idgettr.com/">idGettr</a> to find it.</small>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>">Number of Photos:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
            </p>
        <?php
    }
 
    function update($new_instance, $old_instance) {
        $instance=$old_instance;
 
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['flickr_id']=$new_instance['flickr_id'];
        $instance['number']=$new_instance['number'];
        return $instance;
    }
 
}
 
 
add_action( 'widgets_init', 'rm_load_widgets' );
function rm_load_widgets() {
    register_widget('SIS_Flickr');
}

$instance = wp_parse_args( (array) $instance, array('title' => 'Flickr Photos', 'number' => 5, 'flickr_id' => '') );