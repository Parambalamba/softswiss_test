<?php

defined( 'ABSPATH' ) || exit;

/**
 * Регистрируем свою таксономию и тип записи для ресторанов
 */
add_action('init', 'my_custom_init');
if ( ! function_exists( 'my_custom_init' ) ) {
	function my_custom_init(){
		register_taxonomy( 'restaurant_types', [ 'restaurant' ], [
			'label'                 => 'Виды ресторанов', // определяется параметром $labels->name
			'labels'                => array(
				'name'              => 'Виды ресторанов',
				'singular_name'     => 'Вид ресторана',
				'search_items'      => 'Искать Вид ресторана',
				'all_items'         => 'Все Виды ресторанов',
				'parent_item'       => 'Родит. Вид ресторана',
				'parent_item_colon' => 'Родит. Вид ресторана:',
				'edit_item'         => 'Ред. Вид ресторана',
				'update_item'       => 'Обновить Вид ресторана',
				'add_new_item'      => 'Добавить Вид ресторана',
				'new_item_name'     => 'Новый Вид ресторана',
				'menu_name'         => 'Вид ресторана',
			),
			'description'           => 'Категории ресторанов', // описание таксономии
			'public'                => true,
			'show_in_nav_menus'     => false, // равен аргументу public
			'show_ui'               => true, // равен аргументу public
			'show_tagcloud'         => false, // равен аргументу show_ui
			'hierarchical'          => true,
			//'rewrite'               => array('slug'=>'restaurant_types', 'hierarchical'=>true, 'with_front'=>true, 'feed'=>false ),
			'rewrite' => true,
			'show_admin_column'     => true, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
		] );

		register_post_type('restaurant', array(
			'labels'             => array(
				'name'               => 'Рестораны', // Основное название типа записи
				'singular_name'      => 'Ресторан', // отдельное название записи типа Book
				'add_new'            => 'Добавить новый',
				'add_new_item'       => 'Добавить новый Ресторан',
				'edit_item'          => 'Редактировать Ресторан',
				'new_item'           => 'Новый Ресторан',
				'view_item'          => 'Посмотреть Ресторан',
				'search_items'       => 'Найти Ресторан',
				'not_found'          => 'Ресторанов не найдено',
				'not_found_in_trash' => 'В корзине ресторанов не найдено',
				'parent_item_colon'  => '',
				'menu_name'          => 'Рестораны'

			  ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array('title','editor','thumbnail'),
			'taxonomies'		 => array('restaurant_types')
		) );
	}
}

/**
 * Регистрируем свой размер миниатюр для вывода в таблице
 */
add_image_size( 'mytheme-mini', 25, 25, true );

/**
 * Получаем имя и слаг категории кастомного типа restaurant
 */
function get_cat($id) {
	$terms = get_the_terms($id, 'restaurant_types');
	$result = array();
	if ($terms) {
		foreach ($terms as $term) {
			$result['name'] = $term->name;
			$result['slug'] = $term->slug;
			break;
		}
	}
	return $result;
}

/**
 * Подкючаем поиск через ajax
 */
add_action('wp_ajax_nopriv_test_ajax_search','test_ajax_search');
add_action('wp_ajax_test_ajax_search','test_ajax_search');
function test_ajax_search() {
	$args = array(
		's' => $_POST['search_item'],
		'post_type' => 'restaurant',
	);
	$result = '';
	$search_query = new WP_Query($args);
	if ($search_query->have_posts()) {
		while ($search_query->have_posts()) {
			$search_query->the_post();
			$id = get_the_ID();
			$cat_name = get_cat($id);
			$result .= '<div class="d-flex flex-row col-12 filters-item border border-top-0 border-secondary" data-filter="' . $cat_name['slug'] . '">
									<div class="p-2">' .$id . '</div>
									<div class="p-2 col-md-2 d-flex">' . get_the_post_thumbnail(null, 'mytheme-mini') . '<div class="ml-1">' . get_the_title() . '</div></div>
									<div class="p-2 col-md-2">' . $cat_name['name'] . '</div>
									<div class="p-2 col-md-2">' . get_field('start_date') . '</div>
									<div class="p-2 col-md-1">' . get_field('end_date') . '</div>
									<div class="p-2 col-md-1">' . get_field('total') . '</div>
									<div class="p-2 col-md-1">' . get_field('fees') . '</div>
									<div class="p-2 col-md-1">' . get_field('transfer') . '</div>
									<div class="p-2 col-md-1">' . get_field('orders') . '</div>
								</div>';
		}
	}
    echo json_encode(array(
        'result' => 'success',
        'finded' => $result
    ));
    wp_die();
}
?>