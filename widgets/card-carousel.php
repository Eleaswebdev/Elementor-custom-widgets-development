<?php

class BECard_Carousel_Widget extends \Elementor\Widget_Base {


    public function get_name() {
        return 'becard-carousel';
    }

    public function get_title() {
        return __( 'Be Card Carousel', 'plugin-name' );
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

        $repeater = new \Elementor\Repeater();

         $repeater->add_control(
            'card_image',
            [
                'label' => __( 'Card image', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'show_label' => true,
                 'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                
            ]
        );

        
        
       

        $repeater->add_control(
            'card_title', [
                'label' => __( 'Card Title', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Card title' , 'plugin-domain' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'card_content', [
                'label' => __( 'Card Content', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __( 'Card Content' , 'plugin-domain' ),
                'show_label' => true,
            ]
        );

          $this->add_control(
            'columns',
            [
                'label' => __( 'Columns', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1'  => __( '1 Column', 'plugin-domain' ),
                    '2' => __( '2 Columns', 'plugin-domain' ),
                    '3' => __( '3 Columns', 'plugin-domain' ),
                    '4' => __( '4 Columns', 'plugin-domain' ),
                ],
            ]
        );

       

        $this->add_control(
            'slides',
            [
                'label' => __( 'Card Slides', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'card_title' => __( 'Card #1', 'plugin-domain' ),
                        'card_content' => __( 'Card content', 'plugin-domain' ),
                    ],
                ]
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'setting_section',
            [
                'label' => __( 'Card Carousel Settings', 'plugin-name' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'fade',
            [
                'label' => __( 'Fade effecct?', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'your-plugin' ),
                'label_off' => __( 'No', 'your-plugin' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'loop',
            [
                'label' => __( 'Loop?', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'your-plugin' ),
                'label_off' => __( 'No', 'your-plugin' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'arrows',
            [
                'label' => __( 'Show arrows?', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'your-plugin' ),
                'label_off' => __( 'Hide', 'your-plugin' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'dots',
            [
                'label' => __( 'Show dots?', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'your-plugin' ),
                'label_off' => __( 'Hide', 'your-plugin' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay?', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'your-plugin' ),
                'label_off' => __( 'No', 'your-plugin' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_time',
            [
                'label' => __( 'Autoplay Time', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '5000',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        if($settings['slides']) {
            $dynamic_id = rand(78676, 967698);
            if(count($settings['slides']) > 1) {
                if($settings['fade'] == 'yes') {
                    $fade = 'true';
                } else {
                    $fade = 'false';
                }
                if($settings['arrows'] == 'yes') {
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
                if($settings['loop'] == 'yes') {
                    $loop = 'true';
                } else {
                    $loop = 'false';
                }
                echo '<script>
                    jQuery(document).ready(function($) {
                        $("#card-'.$dynamic_id.'").slick({
                            arrows: '.$arrows.',
                            prevArrow: "<i class=\'fa fa-angle-left\'></i>",
                            nextArrow: "<i class=\'fa fa-angle-right\'></i>",
                            dots: '.$dots.',
                            fade: '.$fade.',
                            slidesToShow: '.$settings['columns'].',
                            autoplay: '.$autoplay.',
                            responsive: [
                                    {
                                      breakpoint: 768,
                                      settings: {
                                      
                                      
                                        
                                        slidesToShow: 2
                                      }
                                    },
                                    {
                                      breakpoint: 480,
                                      settings: {
                                        
                                        
                                       
                                        slidesToShow: 1
                                      }
                                    }
                                  ],
                            loop: '.$loop.',';


                            if($autoplay == 'true') {
                                echo 'autoplaySpeed: '.$settings['autoplay_time'].'';
                            }
                            

                            echo '
                        });
                    });
                </script>';
            }
            echo '<div id="card-'.$dynamic_id.'" class="card row">'; ?>
            <?php foreach( $settings[ 'slides' ] as $slide ) : ?>
                <div class="card-body col-lg-4">
                    <a href="<?php the_permalink(); ?>">    
                      <img src="<?php echo esc_url( $slide[ 'card_image' ][ 'url' ] ); ?>" alt="<?php esc_attr_e( $slide[ 'card_title' ] ); ?>" />
                     </a>   
                    <h4 style="color: ' . $settings['title_color'] . '" class="card-title"><?php echo $slide[ 'card_title' ]; ?></h4>
                    <p style="color: ' . $settings['text_color'] . '" class="card-text"><?php echo $slide[ 'card_content' ]; ?></p>
                </div>
            <?php endforeach; ?>
            <?php
            echo '</div>';
        }
        

    }

}