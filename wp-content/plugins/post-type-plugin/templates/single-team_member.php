<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

if (have_posts()) :
    while (have_posts()) : the_post(); ?>
        <div class="team-member-single">
            <div class="team-member-image">
                <?php the_post_thumbnail('large'); ?>
            </div>
            <div class="team-member-content">
                <h2 class="team-member-name text-success"><?php the_title(); ?></h2>
                <h3 class="team-member-position text-secondary">
                    <?php echo esc_html(get_post_meta(get_the_ID(), '_team_member_position', true)); ?>
                </h3>
                <div class="team-member-bio">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    <?php endwhile;
endif;

get_footer();
