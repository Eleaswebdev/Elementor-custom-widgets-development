<?php

function Be_product_cat_list( ) {

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


class Be_Posts_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'becustom-posts-block';
	}

	public function get_title() {
		return __( 'Beposts', 'plugin-name' );
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

      
          // Image
            $this->add_control(
                'image',
                [
                    'label' => __( 'Choose Image', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                ]
            );

		
			$this->add_control(
				'title',
				[
					'label' => __( 'Title', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => 'Girl Lookbook 2015',
				]
			);
		
			$this->add_control(
				'content',
				[
					'label' => __( 'Content', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::WYSIWYG,
					'default' => 'Default content',
				]
			);

			  $this->add_control(
			'category',
			[
				'label' => __( 'Select Category', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
                'options' => Be_product_cat_list(),
                'default' => [ 'all' ],
			]
        );
        


				// $this->add_control(
				// 	'post_type',
				// 	[
				// 		'label' => __( 'Source', 'plugin-name' ),
				// 		'type' => \Elementor\Controls_Manager::SELECT,
				// 		'options' => $this->get_post_types(),
				// 		'default' => key( $this->get_post_types() ),
				// 	]
				// );

				
	
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style Section', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'style_tabs'
		);

			$this->add_control(
			'title_color',
			[
				'label' => __( 'Card Title Color', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .card-title' => 'color: {{VALUE}}',
				],
			]
		);

			$this->add_control(
			'text_color',
			[
				'label' => __( 'Card Text Color', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .card-text' => 'color: {{VALUE}}',
				],
			]
		);

			   // Background Color
                    $this->add_control(
                        'but_button_hover_bg_color',
                        [
                            'label' => __( 'Card Hover Background Color', 'plugin-domain' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#707070',
                            'selectors' => [
                                '{{WRAPPER}} .card:hover .card-body' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );
              

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'background',
					'label' => __( 'Background', 'plugin-domain' ),
					'types' => [ 'classic', 'gradient', 'video' ],
					'selector' => '{{WRAPPER}} .card-body',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'label' => __( 'Typography', 'plugin-domain' ),
					
					'selector' => '{{WRAPPER}} .card-text',
				]
			);

		  // Border Type
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'image_border',
                    'label' => __( 'Border', 'plugin-domain' ),
                    'selector' => '{{WRAPPER}} .card .card-img-top',
                ]
            );

            // Border Radius
            $this->add_responsive_control(
                'image_border_radius',
                [
                    'label' => __( 'Border Radius', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'default' => [
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .card .card-img-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .card .card-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );



            // Height
            $this->add_control(
                'card_height',
                [
                    'label' => __( 'Height', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'description' => 'Default: 300px',
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 250,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .card .card-img-top' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

		
		$this->end_controls_tab();
		$this->end_controls_section();

	}
	
    
	protected function render() {

          $settings = $this->get_settings_for_display();
           if(empty($settings['category']) OR $settings['category'] == 'all') {
            $cats = '';
        } else {
            $cats = implode(',', $settings['category']);
        }
        ?>
          <?php 
		   // the query
		   $the_query = new WP_Query( array(
		     'category_name' => $cats,
		      //'post_type'  => 'post',
		      'posts_per_page' => '20'
		   )); 
		?>
     
       
		<?php if ( $the_query->have_posts() ) : ?>
		  <div class="row">
			  <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <div class="col-lg-4">
			    <?php the_title(); ?>
			    <?php the_post_thumbnail(); ?>
             </div>
			  <?php endwhile; ?>
			  <?php wp_reset_postdata(); ?>

			<?php else : ?>
			  <p><?php __('No News'); ?></p>
		  </div>
		<?php endif; ?>
			
		

        <?php
		

	}
	 protected function _content_template() {

    }

}