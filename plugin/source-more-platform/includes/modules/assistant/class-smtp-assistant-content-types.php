<?php
/**
 * AI Assistant content-type registration.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant_Content_Types {
	public const KNOWLEDGE_POST_TYPE    = 'smt_ai_knowledge';
	public const CONVERSATION_POST_TYPE = 'smt_ai_conversation';


	/** Make public knowledge entries translatable while keeping conversations language-neutral CRM records. */
	public function register_polylang_support(): void {
		add_filter( 'pll_get_post_types', array( $this, 'polylang_post_types' ), 10, 2 );
	}

	/** @param array<string,string> $types */
	public function polylang_post_types( array $types, bool $is_settings ): array {
		$types[ self::KNOWLEDGE_POST_TYPE ] = self::KNOWLEDGE_POST_TYPE;
		return $types;
	}

	/**
	 * Register private Knowledge and Conversation records without schema changes.
	 */
	public function register(): void {
		register_post_type(
			self::KNOWLEDGE_POST_TYPE,
			array(
				'labels'          => array(
					'name'          => 'AI Knowledge',
					'singular_name' => 'Knowledge Entry',
					'menu_name'     => 'AI Knowledge',
					'add_new_item'  => 'Add Knowledge Entry',
					'edit_item'     => 'Edit Knowledge Entry',
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'smtp-platform',
				'supports'        => array( 'title', 'editor', 'excerpt' ),
				'capability_type' => 'post',
				'map_meta_cap'    => true,
				'menu_icon'       => 'dashicons-welcome-learn-more',
				'show_in_rest'    => false,
			)
		);

		register_post_type(
			self::CONVERSATION_POST_TYPE,
			array(
				'labels'          => array(
					'name'          => 'AI Conversations',
					'singular_name' => 'AI Conversation',
					'menu_name'     => 'AI Conversations',
					'edit_item'     => 'View AI Conversation',
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'smtp-platform',
				'supports'        => array( 'title' ),
				'capability_type' => 'post',
				'map_meta_cap'    => false,
				'capabilities'    => array(
					'edit_post'          => 'manage_options',
					'read_post'          => 'manage_options',
					'delete_post'        => 'manage_options',
					'edit_posts'         => 'manage_options',
					'edit_others_posts'  => 'manage_options',
					'delete_posts'       => 'manage_options',
					'delete_others_posts'=> 'manage_options',
					'publish_posts'      => 'manage_options',
					'read_private_posts' => 'manage_options',
					'create_posts'       => 'do_not_allow',
				),
				'menu_icon'       => 'dashicons-format-chat',
				'show_in_rest'    => false,
			)
		);
	}
}
