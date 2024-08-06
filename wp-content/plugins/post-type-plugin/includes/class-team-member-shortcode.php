<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
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
                'show_see_all' => true,
            ), $atts, 'team_members'
        );

        $args = array(
            'post_type' => 'team_member',
            'posts_per_page' => $atts['number'],
        );

        $query = new WP_Query($args);

        $output = '<div class="team-members">';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $name = get_the_title();
                $bio = get_the_content();
                $position = get_post_meta(get_the_ID(), 'position', true);
                $image = get_the_post_thumbnail(get_the_ID(), 'thumbnail');

                $output .= '<div class="team-member">';
                if ($atts['image_position'] == 'top') {
                    $output .= '<div class="team-member-image">' . $image . '</div>';
                }
                $output .= '<div class="team-member-info">';
                $output .= '<h3 class="team-member-name"><a href="' . get_permalink() . '">' . $name . '</a></h3>';
                $output .= '<p class="team-member-position">' . $position . '</p>';
                $output .= '<p class="team-member-bio">' . $bio . '</p>';
                $output .= '</div>';
                if ($atts['image_position'] == 'bottom') {
                    $output .= '<div class="team-member-image">' . $image . '</div>';
                }
                $output .= '</div>';
            }
            wp_reset_postdata();
        }

        if ($atts['show_see_all']) {
            $output .= '<a class="see-all" href="' . get_post_type_archive_link('team_member') . '">See All</a>';
        }

        $output .= '</div>';
        return $output;
    }

    public function render_team_members($atts) {
        $atts = shortcode_atts(
            array(
                'number' => -1,
                'image_position' => 'top',
                'show_all_button' => 'false',
            ),
            $atts,
            'team_members'
        );

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $query_args = array(
            'post_type' => 'team_member',
            'posts_per_page' => intval($atts['number']),
            'paged' => $paged,
        );

        $query = new WP_Query($query_args);
        ob_start();

        if ($query->have_posts()) {
            echo '<div class="team-members">';

            while ($query->have_posts()) {
                $query->the_post();
                $position = get_post_meta(get_the_ID(), '_team_member_position', true);
                $image_position = $atts['image_position'];

                echo '<div class="team-member">';
                if ($image_position == 'top') {
                    the_post_thumbnail('thumbnail');
                }
                echo '<h3>' . get_the_title() . '</h3>';
                echo '<p><strong>' . esc_html($position) . '</strong></p>';
                the_content();
                if ($image_position == 'bottom') {
                    the_post_thumbnail('thumbnail');
                }
                echo '</div>';
            }

            echo '</div>';

            // Pagination
            echo '<div class="pagination">';
            echo paginate_links(array(
                'total' => $query->max_num_pages,
            ));
            echo '</div>';
        }

        wp_reset_postdata();
        return ob_get_clean();
    }

}
