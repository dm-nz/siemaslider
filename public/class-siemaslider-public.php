<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://lotus.kg
 * @since      1.0.0
 *
 * @package    Siemaslider
 * @subpackage Siemaslider/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Siemaslider
 * @subpackage Siemaslider/public
 * @author     Lotus <hello@lotus.kg>
 */
class Siemaslider_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Siemaslider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Siemaslider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/siemaslider-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Siemaslider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Siemaslider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/siema.min.js', array(), $this->version, false );

	}

	public function init_siema() {
		?>
		<script>
		const siemas = document.querySelectorAll('.siema');

		// Extend prototype with method that adds arrows to DOM
		// Style the arrows with CSS or JS — up to you mate
		Siema.prototype.addArrows = function() {

		  // make buttons & append them inside Siema's container
		  this.prevArrow = document.createElement('a');
		  this.nextArrow = document.createElement('a');
		  this.prevArrow.className = 'siema-prev siema-arrow';
		  this.nextArrow.className = 'siema-next siema-arrow';
		  this.selector.appendChild(this.prevArrow)
		  this.selector.appendChild(this.nextArrow)

		  // event handlers on buttons
		  this.prevArrow.addEventListener('click', () => this.prev());
		  this.nextArrow.addEventListener('click', () => this.next());
		}

		// this is fairly new way of looping through DOM Elements
		// More about ithere: https://pawelgrzybek.com/loop-through-a-collection-of-dom-elements/
		// For better compatibility I suggest using for loop
		for(const siema of siemas) {
		  const instance = new Siema({
		    selector: siema,
		  });
		  instance.addArrows();
		}

		</script>
		<?php
	}

	public function markup_siema( $output, $attr ) {
	    global $post;

	    static $instance = 0;
	    $instance++;

	    if ( isset( $attr['orderby'] ) ) {
	        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
	        if ( !$attr['orderby'] )
	            unset( $attr['orderby'] );
	    }

	    extract( shortcode_atts( array(
	        'order'      => 'ASC',
	        'orderby'    => 'menu_order ID',
	        'id'         => $post->ID,
	        'itemtag'    => 'dl',
	        'icontag'    => 'dt',
	        'captiontag' => 'dd',
	        'columns'    => 3,
	        'size'       => 'thumbnail',
	        'include'    => '',
	        'exclude'    => ''
	    ), $attr ));

	    $id = intval( $id );
	    if ( 'RAND' == $order )
	        $orderby = 'none';

	    if ( !empty($include) ) {
	        $include = preg_replace( '/[^0-9,]+/', '', $include );
	        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

	        $attachments = array();
	        foreach ( $_attachments as $key => $val ) {
	            $attachments[$val->ID] = $_attachments[$key];
	        }
	    } elseif ( !empty($exclude) ) {
	        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
	        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	    } else {
	        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	    }

	    if ( empty( $attachments ) )
	        return '';

		if ( $attr['active-siema'] == "true" ) {
		    $output = '<div class="siema">';
		    foreach ( $attachments as $att_id => $attachment )
		        $output .= '<figure>' . wp_get_attachment_image($att_id, 'large') . '<figcaption>' . wptexturize($attachment->post_excerpt) . '</figcaption></figure>';
		        $output .= '</div>';
			// $output .= '<button class="prev">prev</button><button class="next">next</button>';
			return $output;
		}
	}

}
