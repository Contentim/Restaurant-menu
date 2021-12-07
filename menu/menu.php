<?php
/*
Plugin Name: Меню ресторана
Plugin URI: https://contentim.ru
Description: Позволяет создать на сайте меню ресторана с таксономией по разделам.
Version: 1.0
Author: Иван Гончаренко
Author URI: https://contentim.ru
*/

register_activation_hook(__FILE__, function() {
    // проверяем права пользователя на установку плагинов
    if (!current_user_can('activate_plugins')) {
        return;
    }
});

register_deactivation_hook(__FILE__, function() {
    // проверяем права пользователя на деактивацию плагинов
    if (!current_user_can('deactivate_plugins')) {
        return;
    }
});
 
/*
 * Регистрируем пользовательский тип записи menu
 */
add_action('init', function () {
    $labels = [
        'name' => 'Меню ресторана',
        'menu_name' => 'Меню ресторана',
        'singular_name' => 'Запись',
        'add_new' => 'Добавить запись',
        'add_new_item' => 'Добавить новую запись',
        'edit_item' => 'Редактировать запись',
        'new_item' => 'Новая запись',
        'all_items' => 'Все записи',
        'view_item' => 'Посмотреть запись',
        'search_items' => 'Найти запись',
        'not_found' =>  'Ничего не найдено',
        'not_found_in_trash' => 'В корзине не найдено'
    ];
    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => true,
        'menu_position' => null,
        'supports' => [
            'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'post-formats'
        ],
        'taxonomies' => ['menu'],
    ];
    register_post_type('food', $args);
});

/*
 * Регистрируем иерархическую таксономию по жанрам
 */
add_action('init', function () {
    $labels = array(
        'name'          => 'Разделы меню',
        'singular_name' => 'Раздел',
        'menu_name'     => 'Разделы меню' ,
        'all_items'     => 'Все разделы',
        'edit_item'     => 'Редактировать раздел',
        'view_item'     => 'Посмотреть раздел',
        'update_item'   => 'Сохранить раздел',
        'add_new_item'  => 'Добавить новый раздел',
        'parent_item'   => 'Родительский раздел',
        'search_items'  => 'Поиск по разделам',
        'back_to_items' => 'Назад на страницу разделов',
        'most_used'     => 'Популярные разделы',
    );
    $args = array(
        'labels'            => $labels,
        'show_admin_column' => true,
        'public'               => true,
        'publicly_queryable'   => null,
        'hierarchical'         => true,
        'rewrite'              => true,
        'show_in_rest'         => true // вот оно
    );
    register_taxonomy('menu', ['food'], $args);

    flush_rewrite_rules();
});

require 'Food_Menu_Widget.php';

/*
 * Регистрируем виджет «Жанры книг»
 */
add_action(
    'widgets_init',
    function () {
        register_widget('Food_Menu_Widget');
    }
);