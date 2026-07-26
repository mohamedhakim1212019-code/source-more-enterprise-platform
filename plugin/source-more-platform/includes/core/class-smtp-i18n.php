<?php
/**
 * Public bilingual experience helpers.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_I18n {
	/**
	 * Resolve English or Arabic from an explicit request hint, Polylang, or RTL.
	 */
	public static function language( string $hint = '' ): string {
		$hint = strtolower( sanitize_key( $hint ) );
		if ( in_array( $hint, array( 'ar', 'en' ), true ) ) {
			return $hint;
		}

		if ( function_exists( 'pll_current_language' ) ) {
			$current = (string) pll_current_language( 'slug' );
			if ( in_array( $current, array( 'ar', 'en' ), true ) ) {
				return $current;
			}
		}

		return is_rtl() ? 'ar' : 'en';
	}

	public static function is_ar( string $hint = '' ): bool {
		return 'ar' === self::language( $hint );
	}

	public static function text( string $english, string $arabic, string $hint = '' ): string {
		return self::is_ar( $hint ) ? $arabic : $english;
	}

	/**
	 * Return a page URL in the requested language, preserving Polylang relations.
	 */
	public static function page_url( string $slug, string $hint = '' ): string {
		$lang = self::language( $hint );
		$slug = sanitize_title( $slug );
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $page instanceof WP_Post ) {
			$candidates = get_posts(
				array(
					'post_type'        => 'page',
					'post_status'      => array( 'publish', 'private', 'draft', 'pending', 'future' ),
					'posts_per_page'   => 20,
					'name'             => $slug,
					'suppress_filters' => true,
					'no_found_rows'    => true,
				)
			);
			foreach ( $candidates as $candidate ) {
				if ( ! $candidate instanceof WP_Post ) {
					continue;
				}
				if ( function_exists( 'pll_get_post_language' ) ) {
					$candidate_lang = (string) pll_get_post_language( $candidate->ID, 'slug' );
					if ( $candidate_lang && 'en' !== $candidate_lang ) {
						continue;
					}
				}
				$page = $candidate;
				break;
			}
		}

		if ( $page instanceof WP_Post ) {
			if ( function_exists( 'pll_get_post' ) ) {
				$translated = (int) pll_get_post( $page->ID, $lang );
				if ( $translated ) {
					return (string) get_permalink( $translated );
				}
			}
			return (string) get_permalink( $page );
		}

		$home = function_exists( 'pll_home_url' ) ? (string) pll_home_url( $lang ) : home_url( '/' );
		return user_trailingslashit( trailingslashit( $home ) . trim( $slug, '/' ) );
	}

	/**
	 * Return the Product Center archive inside the active language directory.
	 */
	public static function products_url( string $hint = '' ): string {
		$lang = self::language( $hint );
		if ( function_exists( 'pll_home_url' ) ) {
			return user_trailingslashit( trailingslashit( (string) pll_home_url( $lang ) ) . 'products' );
		}

		$archive = post_type_exists( 'smt_product' ) ? get_post_type_archive_link( 'smt_product' ) : '';
		return $archive ? (string) $archive : self::page_url( 'products', $lang );
	}

	/**
	 * Normalize a request language supplied by public JavaScript.
	 */
	public static function request_language( array $data ): string {
		return self::language( (string) ( $data['language'] ?? '' ) );
	}
}
