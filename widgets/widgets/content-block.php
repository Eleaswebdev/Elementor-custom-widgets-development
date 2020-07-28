<?php

class RRFCommerce_ContentBlock_Widget extends \Elementor\Widget_Base {


	public function get_name() {
		return 'rrfcommerce-content-block';
	}

	public function get_title() {
		return __( 'RRFCommerce ContentBlock', 'plugin-name' );
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
			'theme',
			[
				'label' => __( 'Box theme', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1'  => __( 'Theme 1', 'plugin-domain' ),
					'2' => __( 'Theme 2', 'plugin-domain' ),
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
			'image',
			[
				'label' => __( 'Image background', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			]
		);
		$this->add_control(
			'icon',
			[
				'label' => __( 'Select icon', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::ICON,
                'default' => 'fa fa-angle-double-right'
			]
		);
		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::URL
			]
		);
		

		$this->end_controls_section();

	}

	protected function render() {

        $settings = $this->get_settings_for_display();
        
        if($settings['link']['is_external'] == true) {
            $target= '_blank';
        } else {
            $target= '_self';
        }

        echo '<div class="content-box content-box-theme-'.$settings['theme'].'">
            <div class="content-box-bg" style="background-image:url('.wp_get_attachment_image_url($settings['image']['id'], 'large').')"></div>
            <div class="content-box-content">
                '.wpautop($settings['content']).'
                <h6>'.$settings['title'].'</h6>
                <a href="'.$settings['link']['url'].'" target="'.$target.'"><i class="'.$settings['icon'].'"></i></a>
            </div>
        </div>';
		

	}

}