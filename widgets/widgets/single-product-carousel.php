<?php 
   function BeSingle_product_list( ) {

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


    function BeSingle_product_cat_list( ) {
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

class Be_SingleProductCarousel_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'be-singleproduct-category';
        }
        
        public function get_title() {
            return __( 'Be SingleProductCategory', 'ppm-quickstart' );
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
                    'options' => BeSingle_product_list(),
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
                    'options' => BeSingle_product_cat_list(),
                    'condition' => [
                        'from' => 'category',
                    ],
                ]
            );

        //add columns number list
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
                    'default' => 'yes'
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
            ?>
              <?php  echo ' <script>
                jQuery(document).ready(function($) {
                    $("#product-carousel-'.$rand.'").slick({
                        arrows: '.$arrows.',
                        dots: '.$dots.',
                        autoplay: '.$autoplay.',
                        prevArrow: "<i class=\'fa fa-angle-left\'></i>",
                        nextArrow: "<i class=\'fa fa-angle-right\'></i>"
                    });
                });
            </script>';
            ?>
           <?php  echo '<div id="product-carousel-'.$rand.'" class="product-carousel">'; ?>
                <?php if ( $q->have_posts() ) : ?>
                
                <?php while ( $q->have_posts() ) : $q->the_post(); 
                  global $product;
                   ?>
                    <div class="single-c-product">
                          <div class="row">
                            <div class="col">
                                <div class="product-thumnb-c-inner">
                                    <div class="product-thumnb-c">
                                         <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                                         <span class="c-product-sale">Sale</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col my-auto text-center">
                                 <div class="c-product-info">
                                     <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                     <div class="c-product-price">
                                         <?php echo $product->get_price_html(); ?>
                                     </div>
                                     <div class="c-product-starrating">
                                       <strong><?php woocommerce_template_loop_rating( $loop->post, $product ); ?></strong>
                                    </div>
                                      <div class="product-add-to-cart-c">
                                           <?php woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>
                                      </div>
                                      <div class="product-description">
                                          <?php the_content(); ?>
                                      </div>
                                      
                                 </div>
                            </div>
                          </div>
                    </div>
                      <?php endwhile; ?>
                      <?php wp_reset_postdata(); ?>

                    <?php else : ?>
                      <p><?php __('No News'); ?></p>
                 
                <?php endif; ?>
           <?php
            echo '</div>';
        
        

    }


}

