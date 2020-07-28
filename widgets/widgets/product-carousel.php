<?php
 function avocado_product_list2( ) {

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


    function avocado_product_cat_list2( ) {
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
class Be_ProductCarousel_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'be-product-carousel';
        }
        
        public function get_title() {
            return __( 'Be ProductCarousel', 'plugin-name' );
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
                    'options' => avocado_product_list2(),
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
                    'options' => avocado_product_cat_list2(),
                    'condition' => [
                        'from' => 'category',
                    ],
                ]
            );

            $this->add_control(
                'nav',
                [
                    'label' => __( 'Enable navigation?', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'yes'
                ]
            );

            $this->add_control(
                'dots',
                [
                    'label' => __( 'Enable dots?', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'autoplay',
                [
                    'label' => __( 'Enable autoplay?', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'yes'
                ]
            );

            $this->end_controls_section();

        }

        protected function render() {

            $settings = $this->get_settings_for_display();

            if($settings['from'] == 'category') {
                $q = new WP_Query( array(
                    'posts_per_page' => 10, 
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
                    'posts_per_page' => 10, 
                    'post_type' => 'product',
                    'post__in' => $settings['p_ids'],
                ));
            }

            

            //$q = new WP_Combine_Queries( $args );

            $rand = rand(897987,9879877);

            if($settings['nav'] == 'yes') {
                $arrows = 'true';
            } else {
                $arrows = 'false';
            }

            if($settings['dots'] == 'yes') {
                $dots = 'true';
            } else {
                $dots = 'false';
            }

            if($settings['autoplay'] == 'yes') {
                $autoplay = 'true';
            } else {
                $autoplay = 'false';
            }

            $html = '
            <script>
                jQuery(document).ready(function($) {
                    $("#product-carousel-'.$rand.'").slick({
                        arrows: '.$arrows.',
                        dots: '.$dots.',
                        autoplay: '.$autoplay.',
                        prevArrow: "<i class=\'fa fa-angle-left\'></i>",
                        nextArrow: "<i class=\'fa fa-angle-right\'></i>"
                    });
                });
            </script>
            <div class="product-carousel" id="product-carousel-'.$rand.'">';
                while($q->have_posts()) : $q->the_post();
                global $product;
                    $html .= '<div class="single-c-product">
                        <div class="row">
                            <div class="col">
                            <div class="product-thumnb-c-inner">
                                <div class="product-thumnb-c" style="background-image:url('.get_the_post_thumbnail_url(get_the_ID(), 'medium').')">';

                                if($product->is_on_sale() ) {
                                    $html .= '<span class="c-product-sale">Sale</span>';
                                }

                                $html .='
                                </div>
                            </div>
                            </div>

                            <div class="col my-auto text-center">
                                <div class="c-product-info">
                                    <h3>'.get_the_title().'</h3>
                                    <div class="c-product-price">'.$product->get_price_html().'</div>';
                                    
                                    if($average = $product->get_average_rating()) {
                                        $html .='<div class="c-product-starrating"><div class="star-rating" title="'.sprintf(__( 'Rated %s out of 5', 'woocommerce' ), $average).'"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div></div>';
                                    }

                                    
                                    $html .='

                                    <div class="product-add-to-cart-c">'.do_shortcode('[add_to_cart id="'.get_the_ID().'"]').'</div>
                                </div>
                            </div>
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