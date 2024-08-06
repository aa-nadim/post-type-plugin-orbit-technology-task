<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Custom_Post_Type {

    protected $post_type;
    protected $singular_name;
    protected $plural_name;
    protected $supports = array('title', 'editor', 'thumbnail');
    protected $slug;
    protected $taxonomy = array();

    public function __construct($post_type, $singular_name, $plural_name, $slug, $supports = array(), $taxonomy = array()) {
        $this->post_type = $post_type;
        $this->singular_name = $singular_name;
        $this->plural_name = $plural_name;
        $this->slug = $slug;
        $this->supports = !empty($supports) ? $supports : $this->supports;
        $this->taxonomy = $taxonomy;

        add_action('init', array($this, 'register_post_type'));
        if (!empty($this->taxonomy)) {
            add_action('init', array($this, 'register_taxonomy'));
        }
    }

    public function register_post_type() {
        $labels = array(
            'name' => __($this->plural_name, 'textdomain'),
            'singular_name' => __($this->singular_name, 'textdomain'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'supports' => $this->supports,
            'rewrite' => array('slug' => $this->slug),
        );

        register_post_type($this->post_type, $args);
    }

    public function register_taxonomy() {
        foreach ($this->taxonomy as $taxonomy) {
            $labels = array(
                'name' => __($taxonomy['plural_name'], 'textdomain'),
                'singular_name' => __($taxonomy['singular_name'], 'textdomain'),
            );

            $args = array(
                'hierarchical' => $taxonomy['hierarchical'],
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $taxonomy['slug']),
            );

            register_taxonomy($taxonomy['taxonomy'], array($this->post_type), $args);
        }
    }
}
