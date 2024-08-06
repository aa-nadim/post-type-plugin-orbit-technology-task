<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Team_Member_Settings {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
    }

    public function add_settings_page() {
        add_menu_page(
            __('Team Member Settings', 'textdomain'),
            __('Team Member Settings', 'textdomain'),
            'manage_options',
            'team-member-settings',
            array($this, 'render_settings_page')
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Team Member Settings', 'textdomain'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('team_member_settings_group');
                do_settings_sections('team-member-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
