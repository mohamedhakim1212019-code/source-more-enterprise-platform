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

/** Render the approved Source More logo lockup consistently across the theme. */
function smt_brand_logo(string $context = 'header'): void {
    $is_dark = in_array($context, ['footer', 'dark'], true);
    $surface_class = $is_dark ? 'smt-brand--dark' : 'smt-brand--light';
    $lang = (function_exists('smt_is_ar') && smt_is_ar()) ? 'ar' : 'en';
    $home = function_exists('smt_home_url') ? smt_home_url() : home_url('/');
    $icon = get_template_directory_uri() . '/assets/images/branding/source-more-icon-approved.png?ver=7.9.4';
    $alt = $lang === 'ar' ? 'سورس مور — مصدر واحد. قيمة أكثر.' : 'Source More — One Source. More Value.';
    $loading = $context === 'header' ? 'eager' : 'lazy';
    $fetchpriority = $context === 'header' ? 'high' : 'auto';
    ?>
    <a
      class="smt-brand <?php echo esc_attr($surface_class . ' smt-brand--' . $lang); ?>"
      href="<?php echo esc_url($home); ?>"
      rel="home"
      aria-label="<?php echo esc_attr($alt); ?>"
    >
      <img
        class="smt-brand__icon"
        src="<?php echo esc_url($icon); ?>"
        alt=""
        width="512"
        height="512"
        loading="<?php echo esc_attr($loading); ?>"
        decoding="async"
        fetchpriority="<?php echo esc_attr($fetchpriority); ?>"
        aria-hidden="true"
      >
      <span class="smt-brand__copy">
        <span class="smt-brand__wordmark">
          <?php if ($lang === 'ar') : ?>
            <span class="smt-brand__name" dir="rtl" lang="ar"><strong>سورس</strong><em>مور</em></span>
          <?php else : ?>
            <span class="smt-brand__name" dir="ltr" lang="en"><strong>Source</strong><em>More</em></span>
          <?php endif; ?>
          <svg class="smt-brand__swoosh" viewBox="0 0 116 18" aria-hidden="true" focusable="false">
            <path class="smt-brand__swoosh-navy" d="M2 3c28 9 55 12 83 8 10-1 19-4 29-9-11 8-22 13-34 15C50 20 24 14 2 3z"/>
            <path class="smt-brand__swoosh-gold" d="M80 15c13-2 24-7 34-13-7 8-17 13-31 16-1 0-2 0-3-3z"/>
          </svg>
        </span>
        <span class="smt-brand__tagline" aria-hidden="true">
          <i></i>
          <?php if ($lang === 'ar') : ?>
            <span class="smt-brand__tagline-copy" dir="rtl" lang="ar"><span>مصدر واحد.</span> <em>قيمة أكثر.</em></span>
          <?php else : ?>
            <span class="smt-brand__tagline-copy" dir="ltr" lang="en"><span>One Source.</span> <em>More Value.</em></span>
          <?php endif; ?>
          <i></i>
        </span>
      </span>
    </a>
    <?php
}
