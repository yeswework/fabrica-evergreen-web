<?php

namespace Fabrica\EvergreenWeb;

if (!defined('WPINC')) { die(); }

class Base {

	private static $options;

	public static function init() {
		self::$options = get_option('few-settings');
		if (!isset(self::$options['few_fallback_path']) || strlen(self::$options['few_fallback_path']) == 0)  { return; }
		add_action('template_redirect', array(__CLASS__, 'templateRedirect'));
	}

	public static function templateRedirect() {
		$fallback = self::$options['few_fallback_path'];
		if (is_404()) {
			wp_redirect($fallback . $_SERVER['REQUEST_URI'], 307);
			exit();
		}
	}
}

Base::init();
