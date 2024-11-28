<?php

namespace Fabrica\EvergreenWeb;

if (!defined('WPINC')) { die(); }

class Settings {

	private static $options;

	public static function init() {
		self::$options = get_option('few-settings');
		add_action('admin_menu', array(__CLASS__, 'addAdminMenu'));
		add_action('admin_init', array(__CLASS__, 'register'));
	}

	public static function addAdminMenu() {
		add_options_page(
			'Fabrica Evergreen Web',
			'Fabrica Evergreen Web',
			'manage_options',
			'fabrica_evergreen_web',
			array(__CLASS__, 'renderOptionsPage')
		);
	}

	public static function register() {
		register_setting(
			'few-settings-group',
			'few-settings',
			array('sanitize_callback' => array(__CLASS__, 'sanitize'))
		);
		add_settings_section(
			'few_few-settings-group_section',
			__('Settings', 'fabrica-evergreen-web'),
			array(__CLASS__, 'renderSectionHeading'),
			'few-settings-group'
		);
		add_settings_field(
			'few_fallback_path',
			__('Fallback path', 'fabrica-evergreen-web'),
			array(__CLASS__, 'renderFallbackPathField'),
			'few-settings-group',
			'few_few-settings-group_section'
		);
	}

	public static function sanitize($input) {
		$output = array(
			'few_fallback_path' => ''
		);
		if (isset($input['few_fallback_path'])) {
			$path = trim($input['few_fallback_path']);
			if (strlen($path) > 0 && substr($path, -1) != '/') { // Add trailing slash if missing
				$path .= '/';
			}
			$output['few_fallback_path'] = $path;
		}
		return $output;
	}

	public static function renderSectionHeading() {
		echo esc_html__('<p><a href="https://4042307.org">404 → 307</a> is the technique devised by <a href="https://ar.al/">Aral Balkan</a> to promote an \'evergreen web\', where links do not expire after a site is moved or redeveloped, but automatically redirect to an older version of the site (hosted on a subdomain or different server).</p><p>This is a WordPress implementation of that paradigm (by <a href="http://yeswework.com">Yes we work</a>, who make the <a href="https://yeswework.com">Fabrica</a> series of tools for WordPress content creators and developers).</p><p>Specify the complete fallback path here (including <code>http://</code> or <code>https://</code> as appropriate, as well as a trailing slash) and any links not found on this site will be passed with to the archived version at that path, with a 307 response code.', 'fabrica-evergreen-web');
	}

	public static function renderFallbackPathField() {
		?><input type='text' class='regular-text code' name='few-settings[few_fallback_path]' value='<?php echo isset(self::$options['few_fallback_path']) ? esc_attr(self::$options['few_fallback_path']) : ''; ?>'><?php
	}

	public static function renderOptionsPage() {
		?><form action='options.php' method='post'>
			<div class="wrap">
			<h1>Fabrica Evergreen Web</h1><?php
			settings_fields('few-settings-group');
			do_settings_sections('few-settings-group');
			submit_button('Save');
			?></div>
		</form><?php
	}
}

Settings::init();
