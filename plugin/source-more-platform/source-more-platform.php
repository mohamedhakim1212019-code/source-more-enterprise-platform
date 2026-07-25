<?php
/**
 * Plugin Name: Source More Platform
 * Description: Business platform for fleet assessments, lead management, branded reports, diagnostics, and the Source More AI assistant.
 * Version: 3.7.1
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author: Source More Technology
 * Text Domain: source-more-platform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SMTP_PLATFORM_VERSION', '3.7.1' );
define( 'SMTP_PLATFORM_DB_VERSION', '3.1.0' );
define( 'SMTP_PLATFORM_FILE', __FILE__ );
define( 'SMTP_PLATFORM_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMTP_PLATFORM_URL', plugin_dir_url( __FILE__ ) );

require_once SMTP_PLATFORM_DIR . 'includes/core/class-smtp-loader.php';
require_once SMTP_PLATFORM_DIR . 'includes/core/class-smtp-version.php';
require_once SMTP_PLATFORM_DIR . 'includes/core/class-smtp-module.php';
require_once SMTP_PLATFORM_DIR . 'includes/core/class-smtp-module-registry.php';
require_once SMTP_PLATFORM_DIR . 'includes/core/class-smtp-bootstrap.php';

SMTP_Bootstrap::register();
