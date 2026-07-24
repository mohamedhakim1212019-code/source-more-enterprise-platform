<?php
/**
 * Reusable UI component helpers.
 *
 * @package Source_More_Technology
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function smt_button( $args = array() ) {
	$defaults = array(
		'label'   => __( 'Learn More', 'source-more-technology' ),
		'url'     => '#',
		'style'   => 'primary',
		'icon'    => '',
		'target'  => '',
		'classes' => '',
	);
	$args = wp_parse_args( $args, $defaults );
	$classes = trim( 'btn btn-' . sanitize_html_class( $args['style'] ) . ' ' . $args['classes'] );
	$target  = $args['target'] ? ' target="' . esc_attr( $args['target'] ) . '" rel="noopener"' : '';
	?>
	<a class="<?php echo esc_attr( $classes ); ?>" href="<?php echo esc_url( $args['url'] ); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span><?php echo esc_html( $args['label'] ); ?></span>
		<?php if ( $args['icon'] ) : ?>
			<i class="<?php echo esc_attr( $args['icon'] ); ?>" aria-hidden="true"></i>
		<?php endif; ?>
	</a>
	<?php
}

function smt_section_heading( $args = array() ) {
	$defaults = array(
		'kicker'    => '',
		'title'     => '',
		'text'      => '',
		'alignment' => 'left',
		'theme'     => 'light',
	);
	$args = wp_parse_args( $args, $defaults );
	$classes = 'ui-section-heading is-' . sanitize_html_class( $args['alignment'] ) . ' is-' . sanitize_html_class( $args['theme'] );
	?>
	<header class="<?php echo esc_attr( $classes ); ?>">
		<?php if ( $args['kicker'] ) : ?><span class="section-kicker"><?php echo esc_html( $args['kicker'] ); ?></span><?php endif; ?>
		<?php if ( $args['title'] ) : ?><h2><?php echo esc_html( $args['title'] ); ?></h2><?php endif; ?>
		<?php if ( $args['text'] ) : ?><p><?php echo esc_html( $args['text'] ); ?></p><?php endif; ?>
	</header>
	<?php
}

function smt_icon_card( $args = array() ) {
	$defaults = array(
		'icon'  => 'fa-solid fa-layer-group',
		'title' => '',
		'text'  => '',
		'url'   => '',
		'label' => __( 'Explore solution', 'source-more-technology' ),
		'style' => 'default',
	);
	$args = wp_parse_args( $args, $defaults );
	?>
	<article class="ui-card ui-icon-card is-<?php echo esc_attr( sanitize_html_class( $args['style'] ) ); ?> reveal">
		<div class="ui-card-icon"><i class="<?php echo esc_attr( $args['icon'] ); ?>" aria-hidden="true"></i></div>
		<?php if ( $args['title'] ) : ?><h3><?php echo esc_html( $args['title'] ); ?></h3><?php endif; ?>
		<?php if ( $args['text'] ) : ?><p><?php echo esc_html( $args['text'] ); ?></p><?php endif; ?>
		<?php if ( $args['url'] ) : ?>
			<a class="ui-card-link" href="<?php echo esc_url( $args['url'] ); ?>"><?php echo esc_html( $args['label'] ); ?><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
		<?php endif; ?>
	</article>
	<?php
}
