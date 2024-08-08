<?php
/*
Plugin Name: Post Type Plugin
Description: A plugin to create custom post types (Team Member).
Version: 1.0
Author: Abdul Awal Nadim
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include base and post type classes
require_once plugin_dir_path(__FILE__) . 'includes/class-custom-post-type.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-team-member-cpt.php'; 
require_once plugin_dir_path(__FILE__) . 'includes/class-team-member-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-team-member-settings.php';

// Initialize the Team Member CPT, Shortcode, and Settings
function initialize_team_member_cpt_and_shortcode() {
    $team_member_cpt = new Team_Member_CPT();
    $team_member_shortcode = new Team_Member_Shortcode();
    $team_member_settings = new Team_Member_Settings(); 
}
add_action('plugins_loaded', 'initialize_team_member_cpt_and_shortcode');
