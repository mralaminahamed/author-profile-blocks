<?php
declare(strict_types=1);

namespace AuthorProfileBlocks\Taxonomies;

use AuthorProfileBlocks\Core\Registerable;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class DepartmentTaxonomy implements Registerable {

    const TAXONOMY = 'apbl_department';

    public function register(): void {
        $this->init();
    }

    public function init(): void {
        add_action( 'init', array( $this, 'register_taxonomy' ) );
    }

    public function register_taxonomy(): void {
        register_taxonomy(
            self::TAXONOMY,
            array( 'apbl_team_member', 'user' ),
            array(
                'labels'            => array(
                    'name'          => __( 'Departments', 'author-profile-blocks' ),
                    'singular_name' => __( 'Department', 'author-profile-blocks' ),
                    'add_new_item'  => __( 'Add New Department', 'author-profile-blocks' ),
                    'edit_item'     => __( 'Edit Department', 'author-profile-blocks' ),
                ),
                'hierarchical'      => true,
                'public'            => true,
                'show_ui'           => true,
                'show_in_rest'      => true,
                'rest_base'         => 'apbl-departments',
                'show_admin_column' => true,
                'rewrite'           => array( 'slug' => 'department' ),
            )
        );
    }
}
