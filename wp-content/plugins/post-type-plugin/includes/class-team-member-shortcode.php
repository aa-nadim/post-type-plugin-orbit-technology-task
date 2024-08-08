<?php
if (!defined('ABSPATH')) {
    exit;
}

class Team_Member_Shortcode {

    public function __construct() {
        add_shortcode('team_members', array($this, 'render_shortcode'));
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(
            array(
                'number' => 5,
                'image_position' => 'top',
                'show_all_button' => 'true',
            ), $atts, 'team_members'
        );

        $args = array(
            'post_type' => 'team_member',
            'posts_per_page' => intval($atts['number']),
        );

        $query = new WP_Query($args);

        $output = '<style>
            .team-members {
                display: flex;
                flex-wrap: wrap; 
                gap: 20px;
                margin: -10px;
            }
            .team-member {
                flex: 1 1 calc(16.66% - 20px); 
                box-sizing: border-box;
                padding: 10px;
            }
            .team-member-image {
                text-align: center; 
            }
            .team-member-info {
                text-align: center; 
            }
            .see-all {
                margin-top: 50px;
            }
        </style>';
        
        $output .= '<div class="container">';

        $output .= '<div class="row team-members">';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $name = get_the_title();
                $bio = get_the_content();
                $position = get_post_meta(get_the_ID(), '_team_member_position', true);
                $image = get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class' => 'img-fluid'));

                $output .= '<div class="team-member">';
                if ($atts['image_position'] === 'top') {
                    $output .= '<div class="team-member-image">' . $image . '</div>';
                }
                $output .= '<div class="team-member-info">';
                $output .= '<h3 class="team-member-name"><a href="' . get_permalink() . '">' . $name . '</a></h3>';
                $output .= '<p class="team-member-position">' . esc_html($position) . '</p>';
                $output .= '<p class="team-member-bio">' . $bio . '</p>';
                $output .= '</div>';
                if ($atts['image_position'] === 'bottom') {
                    $output .= '<div class="team-member-image">' . $image . '</div>';
                }
                $output .= '</div>'; // End team-member
            }
            wp_reset_postdata();
        }

        $output .= '</div>'; // End row

        // Check if the "See All" button should be displayed
        if ($atts['show_all_button'] === 'true') {
            $output .= '<div class="see-all text-center">';
            $output .= '<a class="btn btn-primary" href="' . get_post_type_archive_link('team_member') . '">See All</a>';
            $output .= '</div>';
        }

        $output .= '</div>'; // End container
        return $output;
    }
}

