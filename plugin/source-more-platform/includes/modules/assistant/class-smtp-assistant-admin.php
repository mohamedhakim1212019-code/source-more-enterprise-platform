<?php
/**
 * AI Knowledge and Conversation administration.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant_Admin {
	private SMTP_Assistant_Conversations $conversations;
	private bool $registered = false;

	public function __construct( SMTP_Assistant_Conversations $conversations ) {
		$this->conversations = $conversations;
	}

	public function register_hooks(): void {
		if ( $this->registered ) {
			return;
		}

		add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );
		add_action( 'save_post_' . SMTP_Assistant_Content_Types::KNOWLEDGE_POST_TYPE, array( $this, 'save_knowledge' ) );
		add_filter( 'manage_' . SMTP_Assistant_Content_Types::KNOWLEDGE_POST_TYPE . '_posts_columns', array( $this, 'knowledge_columns' ) );
		add_action( 'manage_' . SMTP_Assistant_Content_Types::KNOWLEDGE_POST_TYPE . '_posts_custom_column', array( $this, 'knowledge_column' ), 10, 2 );
		add_filter( 'manage_' . SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE . '_posts_columns', array( $this, 'conversation_columns' ) );
		add_action( 'manage_' . SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE . '_posts_custom_column', array( $this, 'conversation_column' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'conversation_row_actions' ), 10, 2 );
		$this->registered = true;
	}

	public function meta_boxes(): void {
		add_meta_box(
			'smtp_ai_knowledge_settings',
			'Knowledge Matching',
			array( $this, 'knowledge_box' ),
			SMTP_Assistant_Content_Types::KNOWLEDGE_POST_TYPE,
			'side',
			'high'
		);

		add_meta_box(
			'smtp_ai_conversation_messages',
			'Conversation Transcript',
			array( $this, 'conversation_box' ),
			SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE,
			'normal',
			'high'
		);
	}

	public function knowledge_box( $post ): void {
		wp_nonce_field( 'smtp_save_ai_knowledge', 'smtp_ai_knowledge_nonce' );
		$keywords = (string) get_post_meta( $post->ID, '_smtp_ai_keywords', true );
		$priority = absint( get_post_meta( $post->ID, '_smtp_ai_priority', true ) ?: 5 );
		$action   = sanitize_key( (string) get_post_meta( $post->ID, '_smtp_ai_action', true ) );
		$enabled  = '' === (string) get_post_meta( $post->ID, '_smtp_ai_enabled', true ) || '1' === (string) get_post_meta( $post->ID, '_smtp_ai_enabled', true );
		?>
		<p><label for="smtp_ai_keywords"><strong>Keywords and phrases</strong></label></p>
		<textarea class="widefat" id="smtp_ai_keywords" name="smtp_ai_keywords" rows="5" placeholder="managed print, MPS, cost per page"><?php echo esc_textarea( $keywords ); ?></textarea>
		<p class="description">Separate phrases with commas or new lines.</p>
		<p><label for="smtp_ai_priority"><strong>Priority</strong></label></p>
		<input id="smtp_ai_priority" name="smtp_ai_priority" type="number" min="1" max="10" value="<?php echo esc_attr( $priority ); ?>">
		<p><label for="smtp_ai_action"><strong>Optional action</strong></label></p>
		<select class="widefat" id="smtp_ai_action" name="smtp_ai_action">
			<?php foreach ( array( '' => 'None', 'calculator' => 'Fleet Calculator', 'products' => 'Products', 'contact' => 'Contact Page', 'lead_capture' => 'Lead Capture Form' ) as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $action, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<p><label><input type="checkbox" name="smtp_ai_enabled" value="1" <?php checked( $enabled ); ?>> Enabled for assistant matching</label></p>
		<?php
	}

	public function conversation_box( $post ): void {
		$messages = $this->conversations->messages( $post->ID );
		$lead_id  = $this->conversations->lead_id( $post->ID );
		echo '<p><strong>Status:</strong> ' . esc_html( ucfirst( $this->conversations->status( $post->ID ) ) ) . '</p>';
		echo '<p><strong>Last activity:</strong> ' . esc_html( $this->conversations->last_activity( $post->ID ) ?: '—' ) . '</p>';
		if ( $lead_id ) {
			echo '<p><strong>CRM Lead:</strong> <a href="' . esc_url( get_edit_post_link( $lead_id ) ) . '">#' . esc_html( (string) $lead_id ) . '</a></p>';
		}

		if ( ! $messages ) {
			echo '<p>No messages recorded.</p>';
			return;
		}

		echo '<table class="widefat striped"><thead><tr><th style="width:160px">Time</th><th style="width:100px">Role</th><th>Message</th><th style="width:130px">Source</th></tr></thead><tbody>';
		foreach ( $messages as $message ) {
			echo '<tr><td>' . esc_html( $message['timestamp'] ?? '' ) . '</td><td>' . esc_html( ucfirst( $message['role'] ?? '' ) ) . '</td><td style="white-space:pre-wrap">' . esc_html( $message['content'] ?? '' ) . '</td><td>' . esc_html( $message['source'] ?? '' ) . '</td></tr>';
		}
		echo '</tbody></table>';
	}

	public function save_knowledge( int $post_id ): void {
		if ( ! isset( $_POST['smtp_ai_knowledge_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['smtp_ai_knowledge_nonce'] ) );
		if ( ! wp_verify_nonce( $nonce, 'smtp_save_ai_knowledge' ) || ! current_user_can( 'edit_post', $post_id ) || wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, '_smtp_ai_keywords', sanitize_textarea_field( wp_unslash( $_POST['smtp_ai_keywords'] ?? '' ) ) );
		update_post_meta( $post_id, '_smtp_ai_priority', min( 10, max( 1, absint( $_POST['smtp_ai_priority'] ?? 5 ) ) ) );
		$action = sanitize_key( wp_unslash( $_POST['smtp_ai_action'] ?? '' ) );
		update_post_meta( $post_id, '_smtp_ai_action', in_array( $action, array( '', 'calculator', 'products', 'contact', 'lead_capture' ), true ) ? $action : '' );
		update_post_meta( $post_id, '_smtp_ai_enabled', empty( $_POST['smtp_ai_enabled'] ) ? 0 : 1 );
	}

	public function knowledge_columns( array $columns ): array {
		return array(
			'cb'       => $columns['cb'] ?? '<input type="checkbox" />',
			'title'    => 'Knowledge Entry',
			'keywords' => 'Keywords',
			'priority' => 'Priority',
			'enabled'  => 'Enabled',
			'date'     => $columns['date'] ?? 'Date',
		);
	}

	public function knowledge_column( string $column, int $post_id ): void {
		if ( 'keywords' === $column ) {
			echo esc_html( (string) get_post_meta( $post_id, '_smtp_ai_keywords', true ) ?: '—' );
		}
		if ( 'priority' === $column ) {
			echo esc_html( (string) ( get_post_meta( $post_id, '_smtp_ai_priority', true ) ?: 5 ) );
		}
		if ( 'enabled' === $column ) {
			$value = (string) get_post_meta( $post_id, '_smtp_ai_enabled', true );
			echo esc_html( '' === $value || '1' === $value ? 'Yes' : 'No' );
		}
	}

	public function conversation_columns( array $columns ): array {
		return array(
			'cb'       => $columns['cb'] ?? '<input type="checkbox" />',
			'title'    => 'Conversation',
			'status'   => 'Status',
			'messages' => 'Messages',
			'lead'     => 'CRM Lead',
			'activity' => 'Last Activity',
			'date'     => $columns['date'] ?? 'Date',
		);
	}

	public function conversation_column( string $column, int $post_id ): void {
		if ( 'status' === $column ) {
			echo esc_html( ucfirst( $this->conversations->status( $post_id ) ) );
		}
		if ( 'messages' === $column ) {
			echo esc_html( (string) $this->conversations->message_count( $post_id ) );
		}
		if ( 'lead' === $column ) {
			$lead_id = $this->conversations->lead_id( $post_id );
			echo $lead_id ? '<a href="' . esc_url( get_edit_post_link( $lead_id ) ) . '">#' . esc_html( (string) $lead_id ) . '</a>' : '—';
		}
		if ( 'activity' === $column ) {
			echo esc_html( $this->conversations->last_activity( $post_id ) ?: '—' );
		}
	}

	public function conversation_row_actions( array $actions, $post ): array {
		if ( SMTP_Assistant_Content_Types::CONVERSATION_POST_TYPE === $post->post_type ) {
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}
}
