<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
global $wp_query;
$wp_query = new WP_Query(array(
	'post_type'		=>	'restaurant',
	'posts_per_page' => 10,
	'paged' => get_query_var('page') ?: 1

));
$terms = get_terms('restaurant_types');
?>

<div class="wrapper" id="index-wrapper">

	<div class="container-fluid" id="content" tabindex="-1">

		<div class="row">

			<main class="site-main w-100" id="main">

				<div class="container">
					<h1>Invoices</h1>
					<div class="d-flex flex-row justify-content-between mb-2">
						<?php if ($terms) : ?>
							<div class="d-inline-flex flex-nowrap filters">
								<div class="p-2 text-light bg-secondary filters-tab btn" data-filter="all">All</div>
								<?php foreach ($terms as $term) : ?>
									<div class="p-2 text-secondary filters-tab btn" data-filter="<?= $term->slug ?>"><?= $term->name; ?></div>
								<?php endforeach; ?>
							</div>
							<?php get_search_form(); ?>
						<?php endif; ?>
					</div>
					<div class="d-flex flex-row col-md-12 border border-secondary table-head justify-content-between">
						<div class="p-2 col-md-auto col-sm-auto">ID</div>
						<div class="p-2 col-md-3 col-sm-3 mr-1">Restaurant</div>
						<div class="p-2 col-md-auto col-sm-auto">Status</div>
						<div class="p-2 col-md-auto col-sm-auto">Start Date</div>
						<div class="p-2 col-md-auto col-sm-auto">End Date</div>
						<div class="p-2 col-md-auto col-sm-auto">Total</div>
						<div class="p-2 col-md-auto col-sm-auto">Fees</div>
						<div class="p-2 col-md-auto col-sm-auto">Transfer</div>
						<div class="p-2 col-md-auto col-sm-auto">Orders</div>
					</div>
					<div id="content-items" class="d-flex flex-wrap mb-2">
						<?php if (have_posts()) : ?>
							<?php while (have_posts()) : ?>
								<?php the_post(); ?>
								<?php $id = get_the_ID(); ?>
								<?php $cat_name = get_cat($id); ?>
								<div class="d-flex flex-row col-md-12 filters-item border border-top-0 border-secondary justify-content-between" data-filter="<?= $cat_name['slug']; ?>">
									<div class="p-2 col-md-auto"><?= $id; ?></div>
									<div class="p-2 col-md-3 col-sm-3 grow d-flex flex-wrap"><?= get_the_post_thumbnail(null, 'mytheme-mini'); ?><div class="ml-1"><?= get_the_title(); ?></div></div>
									<div class="p-2 col-md-auto"><span class="<?= $cat_name['slug']; ?>"><?= $cat_name['name']; ?></span></div>
									<div class="p-2 col-md-auto col-sm-auto"><?= get_field('start_date'); ?></div>
									<div class="p-2 col-md-auto col-sm-auto"><?= get_field('end_date'); ?></div>
									<div class="p-2 col-md-auto"><?= get_field('total'); ?></div>
									<div class="p-2 col-md-auto"><?= get_field('fees'); ?></div>
									<div class="p-2 col-md-auto"><?= get_field('transfer'); ?></div>
									<div class="p-2 col-md-auto"><?= get_field('orders'); ?></div>
								</div>
							<?php endwhile; ?>

						<?php endif; ?>
					</div>
					<?php understrap_pagination(); ?>
				</div>


			</main><!-- #main -->

			<!-- The pagination component -->

<?php wp_reset_query(); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #index-wrapper -->

<?php
get_footer();
