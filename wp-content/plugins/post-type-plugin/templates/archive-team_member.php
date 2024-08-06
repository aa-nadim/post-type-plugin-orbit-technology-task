<?php
get_header();
?>

<div class="container mt-5 nadsim">
    <div class="row">
        <?php if (have_posts()) : ?>
            <div class="col-12">
                <div class="team-members row">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p class="card-text"><strong><?php echo get_post_meta(get_the_ID(), '_team_member_position', true); ?></strong></p>
                                    <p class="card-text"><?php the_excerpt(); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php the_posts_navigation(); ?>
            </div>
        <?php else : ?>
            <div class="col-12">
                <p><?php _e('No Team Members found', 'textdomain'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
