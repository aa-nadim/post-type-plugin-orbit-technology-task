<?php
if (!defined('ABSPATH')) {
    exit; 
}

class Team_Member_CPT extends Custom_Post_Type {

    public function __construct() {
        $post_type = 'team_member';
        $singular_name = 'Team Member';
        $plural_name = 'Team Members';
        $slug = 'team-members';
        $supports = array('title', 'editor', 'thumbnail');
        $taxonomy = array(
            array(
                'taxonomy' => 'member_type',
                'singular_name' => 'Member Type',
                'plural_name' => 'Member Types',
                'slug' => 'member-type',
                'hierarchical' => true,
            ),
        );

        parent::__construct($post_type, $singular_name, $plural_name, $slug, $supports, $taxonomy);

        // Hook the redirect function to template_redirect
        add_action('template_redirect', array($this, 'redirect_team_member_archive'));

        // Hook the enqueue_assets method to wp_enqueue_scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
        // Add meta box and template loading
        add_action('add_meta_boxes', array($this, 'add_position_meta_box'));
        add_action('save_post', array($this, 'save_position_meta_box'));

        add_filter('single_template', array($this, 'load_single_template'));
    }

    public function redirect_team_member_archive() {
        if (is_post_type_archive('team_member')) {
            wp_redirect(home_url('/team-member/'));
            exit;
        }
    }

    public function add_position_meta_box() {
        add_meta_box(
            'team_member_position',
            __('Position', 'textdomain'),
            array($this, 'render_position_meta_box'),
            'team_member',
            'side',
            'default'
        );
    }

    public function render_position_meta_box($post) {
        wp_nonce_field('save_position_meta_box_nonce', 'position_meta_box_nonce');
        $value = get_post_meta($post->ID, '_team_member_position', true);
        echo '<label for="team_member_position">' . __('Enter the position of the team member', 'textdomain') . '</label>';
        echo '<input type="text" id="team_member_position" name="team_member_position" value="' . esc_attr($value) . '" size="25" />';
    }

    public function save_position_meta_box($post_id) {
        if (!isset($_POST['position_meta_box_nonce']) || !wp_verify_nonce($_POST['position_meta_box_nonce'], 'save_position_meta_box_nonce')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (isset($_POST['post_type']) && 'team_member' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }
        if (isset($_POST['team_member_position'])) {
            $position = sanitize_text_field($_POST['team_member_position']);
            update_post_meta($post_id, '_team_member_position', $position);
        }
    }

    public function load_single_template($single_template) {
        global $post;
        if ($post->post_type === 'team_member') {
            $template_path = plugin_dir_path(__FILE__) . '../templates/single-team_member.php';
            if (file_exists($template_path)) {
                return $template_path;
            }
        }
        return $single_template;
    }


    public function enqueue_assets() {
        if (is_post_type_archive('team_member') || is_singular('team_member')) {
            
            // Enqueue Bootstrap CSS from CDN as fallback
            wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
            
            // Enqueue Custom CSS
            wp_enqueue_style('team-member-styles', plugin_dir_url(__FILE__) . '../assets/css/style.css');
            

            // Enqueue Bootstrap JS from CDN as fallback
            wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);

            // Enqueue Custom JS
            wp_enqueue_script('team-member-scripts', plugin_dir_url(__FILE__) . '../assets/js/script.js', array('jquery'), null, true);
        }
    }
}
