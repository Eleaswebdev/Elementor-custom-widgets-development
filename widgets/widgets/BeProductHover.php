<?php

  function Be_producthover_list( ) {

        $args = wp_parse_args( array(
            'post_type'   => 'product',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ) );
    
        $query_query = get_posts( $args );
    
        $dropdown_array = array();
        if ( $query_query ) {
            foreach ( $query_query as $query ) {
                $dropdown_array[ $query->ID ] = $query->post_title;
            }
        }
    
        return $dropdown_array;
    }


    function be_producthover_cat_list( ) {
        $elements = get_terms( 'product_cat', array('hide_empty' => false) );
        $product_cat_array = array();

        if ( !empty($elements) ) {
            foreach ( $elements as $element ) {
                $info = get_term($element, 'product_cat');
                $product_cat_array[ $info->term_id ] = $info->name;
            }
        }
    
        return $product_cat_array;
    }
class Be_ProductHoverCard_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'be-product-hovercard';
        }
        
        public function get_title() {
            return __( 'BeProduct HoverCard', 'ppm-quickstart' );
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
                    'label' => __( 'Configuration', 'plugin-name' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'from',
                [
                    'label' => __( 'Products from', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'select'  => __( 'Select Products', 'plugin-domain' ),
                        'category'  => __( 'Select Categories', 'plugin-domain' )
                    ],
                    'default' => 'select'
                ]
            );


            $this->add_control(
                'p_ids',
                [
                    'label' => __( 'And/Or Select products', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => Be_producthover_list(),
                    'condition' => [
                        'from' => 'select',
                    ],
                ]
            );


            $this->add_control(
                'cat_ids',
                [
                    'label' => __( 'And/Or Categories', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => be_producthover_cat_list(),
                    'condition' => [
                        'from' => 'category',
                    ],
                ]
            );

            $this->add_control(
                'count',
                [
                    'label' => __( 'Count', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '6'
                ]
            );

            $this->end_controls_section();

        }

        protected function render() {

            $settings = $this->get_settings_for_display();

            if($settings['from'] == 'category') {
                $q = new WP_Query( array(
                    'posts_per_page' => $settings['count'], 
                    'post_type' => 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $settings['cat_ids'],
                        )
                    ),
                ));
            } else {
                $q = new WP_Query( array(
                    'posts_per_page' => $settings['count'], 
                    'post_type' => 'product',
                    'post__in' => $settings['p_ids'],
                ));
            }

            

            $html = '
            <div class="product-hovercard">';
                while($q->have_posts()) : $q->the_post();
                global $product;

                    $html .= '<div class="single-hc-product">
                        <div class="hc-product-base">
                            '.get_the_post_thumbnail(get_the_ID(), 'thumbnail').'
                            <span>
                                <i class="fa fa-angle-down"></i>
                            </span>
                        </div>

                        <div class="product-hovercard-info">
                            <div class="product-thumnb-hc" style="background-image:url('.get_the_post_thumbnail_url(get_the_ID(), 'medium').')"></div>
                            <h4>'.get_the_title().'</h4>
                            <div class="c-product-price">'.$product->get_price_html().'</div>
                            <div class="product-add-to-cart-c">'.do_shortcode('[add_to_cart style="" show_price="FALSE" id="'.get_the_ID().'"]').'</div>
                        </div>
                    </div>';
                endwhile; wp_reset_query();


                $html .= '</div>';

            if($settings['from'] == 'category' && empty($settings['cat_ids'])) {
                $html = '<div class="alert alert-warning"><p>Please select product category</p></div>';  
            } 
            

            echo $html;

        }

    }
