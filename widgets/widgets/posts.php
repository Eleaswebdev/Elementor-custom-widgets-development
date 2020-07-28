<?php
//https://dtbaker.net/blog/creating-your-own-custom-elementor-widgets/
class BEPosts_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'becustom-posts-block';
	}

	public function get_title() {
		return __( 'BECustom Posts', 'plugin-name' );
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
         'section_blog_posts',
         [
            'label' => __( 'Blog Posts', 'elementor-custom-element' ),
            'type' => Controls_Manager::SECTION,
         ]
      );

      $this->add_control(
         'some_text',
         [
            'label' => __( 'Text', 'elementor-custom-element' ),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'title' => __( 'Enter some text', 'elementor-custom-element' ),
            'section' => 'section_blog_posts',
         ]
      );

        $this->add_control(
         'posts_per_page',
         [
            'label' => __( 'Number of Posts', 'elementor-custom-element' ),
            'type' => Controls_Manager::SELECT,
            'default' => 5,
            'section' => 'section_blog_posts',
            'options' => [
               1 => __( 'One', 'elementor-custom-element' ),
               2 => __( 'Two', 'elementor-custom-element' ),
               5 => __( 'Five', 'elementor-custom-element' ),
               10 => __( 'Ten', 'elementor-custom-element' ),
            ]
         ]
      );
				
	
		$this->end_controls_section();

	

	
	}
	
   protected function render( $instance = [] ) {

      // get our input from the widget settings.

      $custom_text = ! empty( $instance['some_text'] ) ? $instance['some_text'] : ' (no text was entered ) ';
      $post_count = ! empty( $instance['posts_per_page'] ) ? (int)$instance['posts_per_page'] : 5;

      ?>

      <h3>My Example Elementor Widget</h3>
      <p>My text was: <?php echo esc_html( $custom_text );?> </p>
      <h3>Some Recent Posts Here:</h3>
      <ul>
         <?php
         $args = array( 'numberposts' => $post_count );
         $recent_posts = wp_get_recent_posts( $args );
         foreach( $recent_posts as $recent ){
            echo '<li><a href="' . esc_url( get_permalink( $recent["ID"] ) ). '">' .   esc_html( $recent["post_title"] ).'</a> </li> ';
         }
         wp_reset_query();
         ?>
      </ul>

      <?php

   }

  

}

