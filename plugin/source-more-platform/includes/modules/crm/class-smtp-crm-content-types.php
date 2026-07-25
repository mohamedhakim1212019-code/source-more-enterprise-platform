<?php
/**
 * CRM content-type registration.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_CRM_Content_Types {
	public const POST_TYPE = 'smt_fleet_lead';

	/**
	 * Register the existing Fleet Lead post type without changing its public contract.
	 */
	public function register(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'          => array(
					'name'          => 'Source More Leads',
					'singular_name' => 'CRM Lead',
					'menu_name'     => 'Source More CRM',
					'add_new_item'  => 'Add CRM Lead',
					'edit_item'     => 'View CRM Lead',
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'smtp-platform',
				'menu_icon'       => 'dashicons-chart-line',
				'supports'        => array( 'title', 'editor' ),
				'capability_type' => 'post',
				'map_meta_cap'    => true,
			)
		);
	}
}
