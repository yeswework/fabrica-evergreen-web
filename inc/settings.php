<?php

namespace Fabrica\EvergreenWeb;

if (!defined('WPINC')) { die(); }

require_once('singleton.php');

class Settings extends Singleton {

	public function init() {
		add_action('admin_menu', array($this, 'addAdminMenu'));
		add_action('admin_init', array($this, 'initSettings'));
	}

	public function addAdminMenu() {
		add_options_page('Fabrica Evergreen Web', 'Fabrica Evergreen Web', 'manage_options', 'fabrica_evergreen_web', array($this, 'renderOptionsPage'));
	}

	public function initSettings() {
		register_setting(
			'few-settings-group',
			'few-settings',
			array('sanitize_callback' => array($this, 'sanitize'))
		);
		add_settings_section(
			'few_few-settings-group_section',
			__('Settings', 'fabrica-evergreen-web'),
			array($this, 'renderSectionHeading'),
			'few-settings-group'
		);

		add_settings_field(
			'few_fallback_path',
			__('Fallback path', 'fabrica-evergreen-web'),
			array($this, 'renderFallbackPathField'),
			'few-settings-group',
			'few_few-settings-group_section'
		);
	}

	public function sanitize($input) {
		$output = array('few_fallback_path' => '');
		if (isset($input['few_fallback_path'])) {
			$path = trim($input['few_fallback_path']);

			// Add trailing slash if missing
			if (substr($path, -1) != '/') {
				$path .= '/';
			}
			$output['few_fallback_path'] = $path;
		}
		return $output;
	}

	public function renderSectionHeading() {
		echo __('<p><a href="https://4042302.org">4042302</a> is the technique devised by <a href="https://ar.al/">Aral Balkan</a> to promote an \'evergreen web\', where links do not expire after a site is moved or redeveloped, but automatically fall back to an older version of the page.</p><p>This is a WordPress implementation of that paradigm (by <a href="http://yeswework.com">Yes we work</a>).</p><p>Specify the complete fallback path here (including <code>http://</code> or <code>https://</code> as appropriate, as well as a trailing slash) and any links not found on this site will be passed with to the archived version at that path, with a 302 response code.', 'fabrica-evergreen-web');
	}

	public function renderFallbackPathField() {
		$options = get_option('few-settings');
		?><input type='text' class='regular-text code' name='few-settings[few_fallback_path]' value='<?php echo isset($options['few_fallback_path']) ? $options['few_fallback_path'] : ''; ?>'><?php
	}

	public function renderOptionsPage() {
		?><form action='options.php' method='post'>
			<div class="wrap">
				<h1>Fabrica Evergreen Web</h1><?php
				settings_fields('few-settings-group');
				do_settings_sections('few-settings-group');
				submit_button('Save');
				?></form>
			</div>
		<?php
	}
}

Settings::instance()->init();
