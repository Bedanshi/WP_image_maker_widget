<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class My_Custom_Widget extends \Elementor\Widget_Base {

    // Widget name, title, icon, and category
    public function get_name() {
        return 'wp_image_maker';
    }

    public function get_title() {
        return __( 'WP Image Rotate', 'wp_image_maker' );
    }

    public function get_icon() {
        return 'eicon-image-rollover';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    // Register widget controls
    protected function _register_controls() {
        // Image control
        $this->start_controls_section(
            'image_section', 
            [
                'label' => __( 'Image Settings', 'wp_image_maker' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image_url', 
            [
                'label' => __( 'Choose Image', 'wp_image_maker' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://cdn.pixabay.com/photo/2024/04/10/22/52/autumn-8688876_1280.jpg',  
                ],
                'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_section();

        // Video control
        $this->start_controls_section(
            'video_section',
            [
                'label' => __( 'Video Settings', 'wp_image_maker' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label' => __( 'Choose Video', 'wp_image_maker' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
				'media_type'=>'video',
                'default' => [
                    'url' => 'http://localhost/scrollmate/wordpress/wp-content/uploads/2025/04/vecteezy_beautiful-cascade-flowing-among-lush-foliage-plants-under_5336115.mp4', 
                ],
                'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_section();

        // Overlay Text control
        $this->start_controls_section(
            'text_section',
            [
                'label' => __( 'Overlay Text Settings', 'wp_image_maker' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_overlay_text',
            [
                'label' => __( 'Do you want to show the overlay text?', 'wp_image_maker' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'wp_image_maker' ),
                'label_off' => __( 'No', 'wp_image_maker' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'overlay_text',
            [
                'label' => __( 'Overlay Text', 'wp_image_maker' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Add your Text Here!', 'wp_image_maker' ),
                'dynamic' => ['active' => true],
                'condition' => [
                    'show_overlay_text' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    // Render widget content on the front-end
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Get the image URL, video URL, and overlay text from widget settings
        $image_url = !empty($settings['image_url']['url']) ? $settings['image_url']['url'] : '';
        $video_url = !empty($settings['video_url']['url']) ? $settings['video_url']['url'] : '';
        $overlay_text = !empty($settings['overlay_text']) ? $settings['overlay_text'] : '';
        $show_overlay_text = isset($settings['show_overlay_text']) && $settings['show_overlay_text'] === 'yes';

        // Generate a unique identifier for each widget instance
        $widget_id = 'wp_image_maker_' . $this->get_id();  // Unique ID for each instance
        ?>
        <div id="<?php echo esc_attr($widget_id); ?>" class="wp_image_maker_widget" style="position: relative; width: 100%; max-width: 640px;">
            <img class="myImage" src="<?php echo esc_url($image_url); ?>" style="width: 100%; height: auto; border-radius: 20px;">
            <video class="myVideo" src="<?php echo esc_url($video_url); ?>" style="display: none; width: 100%; height: auto; border-radius: 20px;" autoplay loop muted></video>
            <?php if ($show_overlay_text) : ?>
                <h3 class="overlay-text"><?php echo esc_html($overlay_text); ?></h3>
            <?php endif; ?>
        </div>

        <style>
            .wp_image_maker_widget {
                position: relative;
                width: 100%;
                max-width: 640px;
            }

            .myImage, .myVideo {
                width: 100%;       
                height: auto;    
                border-radius: 20px;
                display: block;  /* Ensure both are block elements */
            }

            .myVideo {
                display: none; /* Initially hide the video */
            }

            .overlay-text {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%; 
                height: 25%;
                text-align: center;
                color: white;
                font-size: 24px;
                background-color: rgba(0, 0, 0, 0.5); /* Transparent black background */
                padding-top: 10px;
                padding-right: 24px;
                display: none; 
                margin-top: -10px;
                margin-bottom: 10px;
            }

            .wp_image_maker_widget:hover .overlay-text {
                display: block; /* Show the text on hover */
            }

            .wp_image_maker_widget:hover .myImage {
                display: none;  /* Hide the image on hover */
            }

            .wp_image_maker_widget:hover .myVideo {
                display: block; /* Show the video on hover */
            }
        </style>

   <script>
    document.getElementById("<?php echo esc_attr($widget_id); ?>").addEventListener("mouseenter", function() {
        var image = document.querySelector(".myImage");
        var video = document.querySelector(".myVideo");
        var overlayText = document.querySelector(".overlay-text");

        // Hide image and show video when mouse hovers
        image.style.display = "none"; // Hide the image
        video.style.display = "block"; // Show the video
        video.play(); // Play the video

        // Show overlay text if enabled
        overlayText.style.display = "<?php echo $show_overlay_text ? 'block' : 'none'; ?>"; 
    });

    document.getElementById("<?php echo esc_attr($widget_id); ?>").addEventListener("mouseleave", function() {
        var image = document.querySelector(".myImage");
        var video = document.querySelector(".myVideo");
        var overlayText = document.querySelector(".overlay-text");

        // When the mouse leaves, show the image and hide the video
        image.style.display = "block"; // Show the image
        video.style.display = "none"; // Hide the video
        video.pause(); // Pause the video

        // Hide overlay text when mouse leaves
        overlayText.style.display = "none"; // Hide overlay text
    });
</script>




        <?php
    }
}
