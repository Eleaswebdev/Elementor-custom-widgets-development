<?php

function rrfcommerce_product_cat_list( ) {

    $term_id = 'product_cat';
    $categories = get_terms( $term_id );

    $cat_array['all'] = "All Categories";
    if ( !empty($categories) ) {
        foreach ( $categories as $cat ) {
            $cat_info = get_term($cat, $term_id);
            $cat_array[ $cat_info->slug ] = $cat_info->name;
        }
    }

    return $cat_array;
}

class RRFCommerce_Products_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'rrfcommerce-products';
	}

	public function get_title() {
		return __( 'RRFCommerce Products', 'plugin-name' );
	}

	public function get_icon() {
		return 'fa fa-code';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'limit',
			[
				'label' => __( 'Count', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '4',
			]
        );
        
        $this->add_control(
			'columns',
			[
				'label' => __( 'Columns', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'1'  => __( '1 Column', 'plugin-domain' ),
					'2' => __( '2 Columns', 'plugin-domain' ),
					'3' => __( '3 Columns', 'plugin-domain' ),
					'4' => __( '4 Columns', 'plugin-domain' ),
				],
			]
        );
        
        $this->add_control(
			'category',
			[
				'label' => __( 'Select Category', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
                'options' => rrfcommerce_product_cat_list(),
                'default' => [ 'all' ],
			]
        );
        

        $this->add_control(
			'carousel',
			[
				'label' => __( 'Enable Carousel?', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

        $settings = $this->get_settings_for_display();

        if(empty($settings['category']) OR $settings['category'] == 'all') {
            $cats = '';
        } else {
            $cats = implode(',', $settings['category']);
        }

        if($settings['carousel'] == 'yes') {
            
            $dynamic_id = rand(89896,896698);
            echo '<script>
                jQuery(window).load(function(){
                    jQuery("#product-carousel-'.$dynamic_id.' .products").slick({
                        slidesToShow: '.$settings['columns'].'
                    });
                });
            </script><div id="product-carousel-'.$dynamic_id.'">';
        }

        echo do_shortcode('[products category="'.$cats.'" limit="'.$settings['limit'].'" columns="'.$settings['columns'].'"]');
        
        
        if($settings['carousel'] == 'yes') { echo '</div>'; }

	}

}