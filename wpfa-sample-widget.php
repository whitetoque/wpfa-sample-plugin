<?php
/*
Plugin Name: WPFirstAid Sample Widget
Plugin URI: http://wpfirstaid.com
Description: Plugin with multi-widget functionality that displays stuff ...
Version: 0.3.1
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
Text Domain: wpfa-sample
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/** ----------------------------------------------------------------------------
 * WPFirstAid Sample Plugin for WordPress
 *
 * Plugin with multi-widget functionality that displays stuff ... and a lot more
 * things as well.
 *
 * @package     WPFA_Sample
 * @link        https://github.com/Cais/wpfa-sample
 * @version     0.3.1
 * @author      Edward Caissie <edward.caissie@gmail.com>
 * @copyright   Copyright (c) 2010-2012, Edward Caissie
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.
 *
 * You may NOT assume that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to:
 *
 *      Free Software Foundation, Inc.
 *      51 Franklin St, Fifth Floor
 *      Boston, MA  02110-1301  USA
 *
 * The license for this software can also likely be found here:
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

/** ----------------------------------------------------------------------------
 * WPFA Sample TextDomain
 * Make plugin text available for translation (i18n)
 *
 * @package WPFA_Sample
 * @since   0.3
 *
 * @internal Translation files are expected to be found in the plugin root
 * folder / directory.
 * @internal Using "Textdomain: wpfa-sample" in the plugin header section
 * excludes the need for this function call.
 */
load_plugin_textdomain( 'wpfa-sample' );

/** ----------------------------------------------------------------------------
 * Check installed WordPress version for compatibility
 *
 * @package WPFA_Sample
 * @since   0.3
 *
 * @uses    (global) wp_version - current version of WordPress
 *
 * @internal Requires WordPress version 2.8
 * @internal @uses WP_Widget
 */
global $wp_version;
/** @var $exit_message - message to be displayed by 'exit' function */
$exit_message = __( 'WPFA Sample Widget requires WordPress version 2.8 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please Update!</a>', 'wpfa-sample' );
/** Check required version versus current version  */
if ( version_compare( $wp_version, "2.8", "<" ) )
    exit ( $exit_message );
/** End: Check installed WordPress version ---------------------------------- */

/** ----------------------------------------------------------------------------
 * Enqueue Plugin Scripts and Styles
 * Adds plugin stylesheet and allows for custom stylesheet to be added by
 * end-user. These stylesheets will only affect public facing output.
 *
 * @package WPFA_Sample
 * @since   0.3
 *
 * @uses    plugin_dir_path
 * @uses    plugin_dir_url
 * @uses    wp_enqueue_style
 *
 * @internal JavaScripts, etc. would be added via this same function call using
 * wp_enqueue_script functionality
 * @internal Used with action hook: wp_enqueue_scripts
 */
function WPFA_Sample_Scripts_and_Styles() {
    /** Enqueue Scripts */
    /** Enqueue Style Sheets */
    wp_enqueue_style( 'WPFA-Sample-Style', plugin_dir_url( __FILE__ ) . 'wpfa-sample-style.css', array(), '0.3', 'screen' );
    /** Check if custom stylesheet is readable (exists) */
    if ( is_readable( plugin_dir_path( __FILE__ ) . 'wpfa-sample-custom-style.css' ) ) {
        wp_enqueue_style( 'WPFA-Sample-Custom-Style', plugin_dir_url( __FILE__ ) . 'wpfa-sample-custom-style.css', array(), '0.3', 'screen' );
    }
}
add_action( 'wp_enqueue_scripts', 'WPFA_Sample_Scripts_and_Styles' );
/** End: Enqueue Plugin Scripts and Styles ---------------------------------- */

/** Start Class Extension --------------------------------------------------- */
class WPFA_Sample_Widget extends WP_Widget {

    /** ------------------------------------------------------------------------
     * Create Widget
     * This creates the drag-and-drop block on the Appearance | Widgets panel.
     * It can be placed in an appropriate widget area typically defined by the
     * active theme. This also dictates the size (read: width) of the widget
     * instance option panel.
     */
    function WPFA_Sample_Widget() {
        /** Widget settings. */
        $widget_ops = array( 'classname' => 'wpfa-sample', 'description' => __( 'Displays some stuff.', 'wpfa-sample' ) );
        /** Widget control settings. */
        $control_ops = array( 'width' => 200, 'id_base' => 'wpfa-sample' );
        /** Create the widget. */
        $this->WP_Widget( 'wpfa-sample', 'WPFirstAid Sample', $widget_ops, $control_ops );
    }
    /** End: Create Widget -------------------------------------------------- */

    /** ------------------------------------------------------------------------
     * Overrides 'widget' method from WP_Widget class
     * Everything the widget is going to output or display will be handled by
     * this section.
     *
     * @param   array $args
     * @param   array $instance
     *
     * @var $before_widget string
     * @var $after_widget string
     * @var $before_title string
     * @var $after_title string
     * @var $title string
     * @internal above vars are either drawn from the theme register_sidebar
     * definition, or are drawn from the defaults in WordPress core.
     *
     * @uses    apply_filters
     */
    function widget( $args, $instance ) {
        extract( $args );
        /** User-selected settings. */
        $title        = apply_filters( 'widget_title', $instance['title'] );
        $choices      = $instance['choices'];
        $show_choices = $instance['show_choices'];
        $optionals    = $instance['optionals'];

        /** Before widget (defined by themes). */
        /** @var $before_widget string - defined by theme */
        echo $before_widget;

        /** Widget title */
        if ( $title )
            /**
             * @var $before_title string - defined by theme | WordPress core
             * @var $after_title string - defined by theme | WordPress core
             */
            echo $before_title . $title . $after_title;

        /** Display stuff based on widget settings. */
        if ( $show_choices ) {
            printf( '<span class="wpfa-sample-choices">' . __( '%1$s is in ... step to your %2$s', 'wpfa-sample' ) . '</span>', $choices, $optionals );
        } else {
            echo __( 'No appointments today.', 'wpfa-sample' );
        }

        /** After widget (defined by themes). */
        echo $after_widget;
    }
    /** End: widget method override ----------------------------------------- */

    /** ------------------------------------------------------------------------
     * Update a particular instance of the widget.
     *
     * This function should check that $new_instance is set correctly. The newly
     * calculated value of $instance should be returned. If "false" is returned,
     * the instance won't be saved/updated.
     *
     * @package WPFA_Sample
     * @since   0.1
     *
     * @internal override the 'update' method from WP_Widget class
     *
     * @param   array $new_instance
     * @param   array $old_instance
     *
     * @return  array - Settings to save or bool false to cancel saving
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        /** Strip tags (if needed) and update the widget settings. */
        $instance['title']          = strip_tags( $new_instance['title'] );
        $instance['choices']        = strip_tags( $new_instance['choices'] );
        $instance['show_choices']   = $new_instance['show_choices'];
        $instance['optionals']      = $new_instance['optionals'];

        return $instance;
    }
    /** End: update override ------------------------------------------------ */

    /** ------------------------------------------------------------------------
     * This function displays the widget option panel form used to update the
     * widget settings.
     *
     * @package WPFA_Sample
     * @since   0.1
     *
     * @internal override the 'form' method from WP_Widget class
     *
     * @param   array $instance
     *
     * @uses    __ - to use text within another function
     * @uses    _e - used to echo text to screen
     * @uses    checked
     * @uses    get_field_id
     * @uses    selected
     *
     * @return  string|void
     */
    function form( $instance ) {
        /** Set default widget settings. */
        $defaults = array(
            'title'         => __( 'WPFirstAid Sample', 'wpfa-sample' ),
            'choices'       => __( 'The Doctor', 'wpfa-sample' ),
            'show_choices'  => true,
            'optionals'     => 'right'
        );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

    <p><!-- This (text field) is used to optionally change the title used by the widget -->
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wpfa-sample' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
    </p>

    <p><!-- This (text field) is used to (optionally) change the 'choices' option text -->
        <label for="<?php echo $this->get_field_id( 'choices' ); ?>"><?php _e( 'Make your choices:', 'wpfa-sample' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'choices' ); ?>" name="<?php echo $this->get_field_name( 'choices' ); ?>" value="<?php echo $instance['choices']; ?>" style="width:100%;" />
    </p>

    <p><!-- This (checkbox) is used to turn on or off if the message is displayed -->
        <input class="checkbox" type="checkbox" <?php checked( (bool) $instance['show_choices'], true ); ?> id="<?php echo $this->get_field_id( 'show_choices' ); ?>" name="<?php echo $this->get_field_name( 'show_choices' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_choices' ); ?>"><?php _e( 'Show your choices?', 'wpfa-sample' ); ?></label>
    </p>

    <p><!-- This (dropdown menu) is used to choose "right" or "left" -->
        <label for="<?php echo $this->get_field_id( 'optionals' ); ?>"><?php _e( 'Options:', 'wpfa-sample' ); ?></label>
        <select id="<?php echo $this->get_field_id( 'optionals' ); ?>" name="<?php echo $this->get_field_name( 'optionals' ); ?>" class="widefat" style="width:100%;">
            <option <?php selected( 'right', $instance['optionals'], true ); ?>>right</option>
            <option <?php selected( 'left', $instance['optionals'], true ); ?>>left</option>
        </select>
    </p>

    <?php
    }
    /** End: form override -------------------------------------------------- */
}
/** End: Class extension ---------------------------------------------------- */

/** ----------------------------------------------------------------------------
 * We need to take the widget code (read: the class WPFA_Sample_Widget that
 * extends the WP_Widget class) and register it as a widget. Once the widget is
 * registered it can be added to the widget initialization action.
 *
 * The following is more common practice than intuitive; the add_action call and
 * the function used to 'register_widget' can be placed after the actual class
 * code and it will still work correctly.
 */

/** Register the WP_Widget extended class. */
function load_wpfa_sample_widget() {
    register_widget( 'WPFA_Sample_Widget' );
}

/** Hook registered widget to the widgets_init action. */
add_action( 'widgets_init', 'load_wpfa_sample_widget' );
/** End: register_widget and add to the widget_init call -------------------- */

/** ----------------------------------------------------------------------------
 * WPFA Sample Shortcode
 * Adds shortcode functionality by using the PHP output buffer methods to
 * capture `the_widget` output and return the data to be displayed via the use
 * of the `wpfa_sample` shortcode.
 *
 * @package WPFA_Sample
 * @since   0.2
 *
 * @uses    the_widget
 * @uses    shortcode_atts
 *
 * @internal used with add_shortcode
 *
 * @version 0.3
 * @date    July 20, 2012
 * Set title parameter to null for aesthetic purposes
 */
function wpfa_sample_shortcode ( $atts ) {
    /** Start output buffer capture */
    ob_start(); ?>
    <div class="wpfa-sample-shortcode">
        <?php
        /**
         * Use 'the_widget' as the main output function to be captured
         * @link http://codex.wordpress.org/Function_Reference/the_widget
         */
        the_widget(
            /** The widget name as defined in the class extension */
            'WPFA_Sample_Widget',
            /**
             * The default options (as the shortcode attributes array) to be
             * used with the widget
             */
            $instance = shortcode_atts(
                array(
                    /** Set title to null for aesthetic reasons */
                    'title'         => __( '', 'wpfa-sample' ),
                    'choices'       => __( 'The Doctor', 'wpfa-sample' ),
                    'show_choices'  => true,
                    'optionals'     => 'right',
                ),
                $atts
            ),
            /**
             * Override the widget arguments and set to null. This will set the
             * theme related widget definitions to null for aesthetic purposes.
             */
            $args = array (
                'before_widget'   => '',
                'before_title'    => '',
                'after_title'     => '',
                'after_widget'    => ''
            ) ); ?>
    </div><!-- .wpfa-sample-shortcode -->
    <?php
    /** End the output buffer capture and save captured data into variable */
    $wpfa_sample_output = ob_get_contents();
    /** Stop output buffer capture and clear properly */
    ob_end_clean();

    /** Return the output buffer data for use with add_shortcode output */
    return $wpfa_sample_output;
}
/** Register shortcode */
add_shortcode( 'wpfa_sample', 'wpfa_sample_shortcode' );
/** End: WPFA Sample Shortcode ---------------------------------------------- */