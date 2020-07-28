<?php 
   function be_posts_list1( ) {

        $args = wp_parse_args( array(
            'post_type'   => array( 'post', 'product' ),
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


    function be_postsproduct_cat_list1( ) {
       $elements = get_terms( array('product_cat','category'), array('hide_empty' => false) );
        $product_cat_array = array();

        if ( !empty($elements) ) {
            foreach ( $elements as $element ) {
                $info = get_term($element, 'product_cat','category');
                $product_cat_array[ $info->term_id ] = $info->name;
            }
        }
    
        return $product_cat_array;
    }

class Be_PostProduct_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'be-posts-product-category';
        }
        
        public function get_title() {
            return __( 'Be PostsProduct', 'ppm-quickstart' );
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
                    'label' => __( 'Posts from', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'select'  => __( 'Select Posts', 'plugin-domain' ),
                        'category'  => __( 'Select Categories', 'plugin-domain' )
                    ],
                    'default' => 'select'
                ]
            );


            $this->add_control(
                'p_ids',
                [
                    'label' => __( 'And/Or Select Posts', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => be_posts_list1(),
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
                    'options' => be_postsproduct_cat_list1(),
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
            if($settings['columns'] == '4') {
                $columns_markup = 'col-lg-3';
            } else if($settings['columns'] == '3') {
                $columns_markup = 'col-lg-4';
            } else if($settings['columns'] == '2') {
                $columns_markup = 'col-lg-6';
            } else {
                $columns_markup = 'col-12';
            }

         if($settings['from'] == 'category') {
                $q = new WP_Query( array(
                    'posts_per_page' => 10, 
                    'post_type' => array('post','product'),
                    
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat','category',
                            'field'    => 'term_id',
                            'terms'    => $settings['cat_ids'],
                        )
                    ),
                ));
            }
             else {
                $q = new WP_Query( array(
                    'posts_per_page' => 10, 
                    'post_type' => array( 'post', 'product' ),
                  
                    'post__in' => $settings['p_ids'],
                ));
            }
           ?>
     
       
        <?php if ( $q->have_posts() ) : ?>
          <div class="row">
              <?php while ( $q->have_posts() ) : $q->the_post(); 
               global $product;
                ?>
            <div class="<?php echo $columns_markup; ?> product-details">
              
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
               
             </div>
              <?php endwhile; ?>
              <?php wp_reset_postdata(); ?>

            <?php else : ?>
              <p><?php __('No News'); ?></p>
          </div>
        <?php endif; ?>
            
        

        <?php
        

    }

     

    }

