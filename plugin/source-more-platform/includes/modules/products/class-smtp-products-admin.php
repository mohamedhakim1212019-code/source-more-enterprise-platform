<?php
/**
 * Product Center administration hooks and screens.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Products_Admin {
	private bool $registered = false;

	/**
	 * Register Product Center admin hooks exactly once.
	 */
	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );
		add_action( 'save_post_' . SMTP_Products_Content_Types::POST_TYPE, array( $this, 'save_product' ) );
		add_action( 'save_post_' . SMTP_Products_Content_Types::RFQ_TYPE, array( $this, 'save_rfq' ) );
		add_filter( 'manage_' . SMTP_Products_Content_Types::POST_TYPE . '_posts_columns', array( $this, 'product_columns' ) );
		add_action( 'manage_' . SMTP_Products_Content_Types::POST_TYPE . '_posts_custom_column', array( $this, 'product_column' ), 10, 2 );
		add_filter( 'manage_' . SMTP_Products_Content_Types::RFQ_TYPE . '_posts_columns', array( $this, 'rfq_columns' ) );
		add_action( 'manage_' . SMTP_Products_Content_Types::RFQ_TYPE . '_posts_custom_column', array( $this, 'rfq_column' ), 10, 2 );

		$this->registered = true;
	}

	public function meta_boxes(): void {
		add_meta_box(
			'smtp_product_details',
			'Product Details',
			array( $this, 'product_box' ),
			SMTP_Products_Content_Types::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'smtp_rfq_details',
			'Quote Request Details',
			array( $this, 'rfq_box' ),
			SMTP_Products_Content_Types::RFQ_TYPE,
			'normal',
			'high'
		);
	}

	public function product_box( $post ): void {
		wp_nonce_field( 'smtp_save_product', 'smtp_product_nonce' );

		$fields = array(
			'model'        => array( 'Model / SKU', 'text' ),
			'product_type' => array( 'Product Type', 'select' ),
			'availability' => array( 'Availability', 'select' ),
			'price_mode'   => array( 'Sales Mode', 'select' ),
			'price'        => array( 'Indicative Price (EGP)', 'number' ),
			'brochure_url' => array( 'Brochure / Datasheet URL', 'url' ),
			'key_specs'    => array( 'Key Specifications (one per line)', 'textarea' ),
			'featured'     => array( 'Featured Product', 'checkbox' ),
		);

		echo '<table class="form-table"><tbody>';

		foreach ( $fields as $key => $field ) {
			$value = get_post_meta( $post->ID, '_smtp_' . $key, true );
			echo '<tr><th><label for="smtp_' . esc_attr( $key ) . '">' . esc_html( $field[0] ) . '</label></th><td>';

			if ( 'textarea' === $field[1] ) {
				echo '<textarea class="large-text" rows="6" id="smtp_' . esc_attr( $key ) . '" name="smtp_' . esc_attr( $key ) . '">' . esc_textarea( $value ) . '</textarea>';
			} elseif ( 'checkbox' === $field[1] ) {
				echo '<label><input type="checkbox" id="smtp_' . esc_attr( $key ) . '" name="smtp_' . esc_attr( $key ) . '" value="1" ' . checked( $value, '1', false ) . '> Highlight in Product Center</label>';
			} elseif ( 'product_type' === $key ) {
				echo '<select id="smtp_product_type" name="smtp_product_type">';
				echo '<option value="hardware" ' . selected( $value, 'hardware', false ) . '>Hardware</option>';
				echo '<option value="software" ' . selected( $value, 'software', false ) . '>Software</option>';
				echo '<option value="service" ' . selected( $value, 'service', false ) . '>Service / Subscription</option>';
				echo '</select>';
			} elseif ( 'availability' === $key ) {
				echo '<select id="smtp_availability" name="smtp_availability">';
				echo '<option value="available" ' . selected( $value, 'available', false ) . '>Available</option>';
				echo '<option value="on-request" ' . selected( $value, 'on-request', false ) . '>Available on Request</option>';
				echo '<option value="coming-soon" ' . selected( $value, 'coming-soon', false ) . '>Coming Soon</option>';
				echo '<option value="end-of-life" ' . selected( $value, 'end-of-life', false ) . '>End of Life</option>';
				echo '</select>';
			} elseif ( 'price_mode' === $key ) {
				echo '<select id="smtp_price_mode" name="smtp_price_mode">';
				echo '<option value="quote" ' . selected( $value, 'quote', false ) . '>Request a Quote</option>';
				echo '<option value="direct" ' . selected( $value, 'direct', false ) . '>Outright Sale</option>';
				echo '<option value="subscription" ' . selected( $value, 'subscription', false ) . '>Subscription</option>';
				echo '</select>';
			} else {
				echo '<input class="regular-text" type="' . esc_attr( $field[1] ) . '" id="smtp_' . esc_attr( $key ) . '" name="smtp_' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
			}

			echo '</td></tr>';
		}

		echo '</tbody></table>';
	}

	public function save_product( int $post_id ): void {
		if ( ! isset( $_POST['smtp_product_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['smtp_product_nonce'] ) );

		if ( ! wp_verify_nonce( $nonce, 'smtp_save_product' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) || wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		$map = array(
			'model'        => 'text',
			'product_type' => 'key',
			'availability' => 'key',
			'price_mode'   => 'key',
			'price'        => 'float',
			'brochure_url' => 'url',
			'key_specs'    => 'textarea',
		);

		foreach ( $map as $key => $type ) {
			$raw = isset( $_POST[ 'smtp_' . $key ] ) ? wp_unslash( $_POST[ 'smtp_' . $key ] ) : '';

			if ( 'url' === $type ) {
				$value = esc_url_raw( $raw );
			} elseif ( 'float' === $type ) {
				$value = (string) max( 0, (float) $raw );
			} elseif ( 'textarea' === $type ) {
				$value = sanitize_textarea_field( $raw );
			} elseif ( 'key' === $type ) {
				$value = sanitize_key( $raw );
			} else {
				$value = sanitize_text_field( $raw );
			}

			update_post_meta( $post_id, '_smtp_' . $key, $value );
		}

		update_post_meta( $post_id, '_smtp_featured', isset( $_POST['smtp_featured'] ) ? '1' : '0' );
	}

	public function rfq_box( $post ): void {
		$keys = array(
			'company'      => 'Company',
			'contact_name' => 'Contact',
			'email'        => 'Email',
			'phone'        => 'Phone',
			'product_name' => 'Product',
			'quantity'     => 'Quantity',
			'message'      => 'Requirements',
			'status'       => 'Status',
			'source_url'   => 'Source URL',
		);

		echo '<table class="widefat striped"><tbody>';

		foreach ( $keys as $key => $label ) {
			echo '<tr><th style="width:220px">' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( get_post_meta( $post->ID, '_smtp_' . $key, true ) ) ) . '</td></tr>';
		}

		echo '</tbody></table>';
	}

	public function save_rfq( int $post_id ): void {
		// Quote requests are created through the REST API; admin edits use the core editor only.
	}

	public function product_columns( array $columns ): array {
		return array(
			'cb'           => $columns['cb'] ?? '<input type="checkbox" />',
			'title'        => 'Product',
			'type'         => 'Type',
			'model'        => 'Model',
			'brand'        => 'Brand',
			'availability' => 'Availability',
			'date'         => $columns['date'] ?? 'Date',
		);
	}

	public function product_column( string $column, int $post_id ): void {
		if ( 'type' === $column ) {
			echo esc_html( ucfirst( get_post_meta( $post_id, '_smtp_product_type', true ) ?: 'hardware' ) );
		}

		if ( 'model' === $column ) {
			echo esc_html( get_post_meta( $post_id, '_smtp_model', true ) );
		}

		if ( 'brand' === $column ) {
			$terms = get_the_terms( $post_id, SMTP_Products_Content_Types::BRAND );
			echo esc_html( $terms && ! is_wp_error( $terms ) ? implode( ', ', wp_list_pluck( $terms, 'name' ) ) : '—' );
		}

		if ( 'availability' === $column ) {
			echo esc_html( ucwords( str_replace( '-', ' ', get_post_meta( $post_id, '_smtp_availability', true ) ?: 'available' ) ) );
		}
	}

	public function rfq_columns( array $columns ): array {
		return array(
			'cb'      => $columns['cb'] ?? '<input type="checkbox" />',
			'title'   => 'Request',
			'company' => 'Company',
			'contact' => 'Contact',
			'product' => 'Product',
			'status'  => 'Status',
			'date'    => $columns['date'] ?? 'Date',
		);
	}

	public function rfq_column( string $column, int $post_id ): void {
		if ( 'company' === $column ) {
			echo esc_html( get_post_meta( $post_id, '_smtp_company', true ) );
		}

		if ( 'contact' === $column ) {
			echo esc_html( get_post_meta( $post_id, '_smtp_contact_name', true ) ) . '<br><small>' . esc_html( get_post_meta( $post_id, '_smtp_email', true ) ) . '</small>';
		}

		if ( 'product' === $column ) {
			echo esc_html( get_post_meta( $post_id, '_smtp_product_name', true ) );
		}

		if ( 'status' === $column ) {
			echo '<strong>' . esc_html( ucfirst( get_post_meta( $post_id, '_smtp_status', true ) ?: 'new' ) ) . '</strong>';
		}
	}
}
