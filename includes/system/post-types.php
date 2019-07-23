<?php

namespace system;

class Post_Types
{

    public static function init()
    {
        add_action( 'setup_theme', [ __CLASS__, 'register_post_types' ], 5 );

    }

    public static function register_post_types()
    {
        // if( ! is_blog_installed() || post_type_exists( 'tempes' ) ){
        //     return;
        // }

        // do_action( 'p_register_post_types' );

        // $supports   = array( 'title', 'thumbnail' );

        // $has_archive = true;

        // register_post_type(
        //     'tempes',
        //     [
        //         'labels' => [
        //             'name'                  => __( 'Products', 'tempo' ),
        //             'singular_name'         => __( 'Tempo Product', 'tempo' ),
        //             'all_items'             => __( 'All Tempo Products', 'tempo' ),
        //             'menu_name'             => _x( 'Tempo Products', 'Admin menu name', 'tempo' ),
        //             'add_new'               => __( 'Add New', 'tempo' ),
        //             'add_new_item'          => __( 'Add new tempo product', 'tempo' ),
        //             'edit'                  => __( 'Edit', 'tempo' ),
        //             'edit_item'             => __( 'Edit tempo product', 'tempo' ),
        //             'new_item'              => __( 'New tempo product', 'tempo' ),
        //             'view_item'             => __( 'View tempo product', 'tempo' ),
        //             'view_items'            => __( 'View tempo products', 'tempo' ),
        //             'search_items'          => __( 'Search tempo products', 'tempo' ),
        //             'not_found'             => __( 'No tempo products found', 'tempo' ),
        //             'not_found_in_trash'    => __( 'No tempo products found in trash', 'tempo' ),
        //             'parent'                => __( 'Parent tempo product', 'tempo' ),
        //             'featured_image'        => __( 'Tempo Product image', 'tempo' ),
        //             'set_featured_image'    => __( 'Set tempo product image', 'tempo' ),
        //             'remove_featured_image' => __( 'Remove tempo product image', 'tempo' ),
        //             'use_featured_image'    => __( 'Use as tempo product image', 'tempo' ),
        //             'insert_into_item'      => __( 'Insert into tempo product', 'tempo' ),
        //             'uploaded_to_this_item' => __( 'Uploaded to this tempo product', 'tempo' ),
        //             'filter_items_list'     => __( 'Filter tempo products', 'tempo' ),
        //             'items_list_navigation' => __( 'Tempo Products navigation', 'tempo' ),
        //             'items_list'            => __( 'Tempo Products list', 'tempo' ),
        //         ],
        //         'description'         => '',
        //         'public'              => true,
        //         'show_ui'             => true,
        //         'capability_type'     => 'tempes',
        //         'map_meta_cap'        => true,
        //         'publicly_queryable'  => true,
        //         'exclude_from_search' => false,
        //         'hierarchical'        => false,
        //         'query_var'           => true,
        //         'rewrite'             => [
        //             'slug' => 'products',
        //             'with_front ' => false,
        //         ],
        //         'supports'            => $supports,
        //         'has_archive'         => 'products',
        //         'show_in_nav_menus'   => true,
        //         'show_in_rest'        => true,
        //         'show_in_menu'        => 'tempo',
        //     ]
        // );

    }

}