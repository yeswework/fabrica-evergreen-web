<?php

namespace Fabrica\EvergreenWeb;

if (!defined('WPINC')) { die(); }

require_once('singleton.php');

class Base extends Singleton {

	public function init() {
		$options = get_option('few-settings');
		if (!isset($options['few_fallback_path'])) { return; }
		add_action('template_redirect', array($this, 'templateRedirect'));
	}

	public function templateRedirect() {
		$options = get_option('few-settings');
		if (!isset($options['few_fallback_path'])) { return; }
		$fallback = $options['few_fallback_path'];
		if ($fallback && is_404()) {
			wp_redirect($fallback . $_SERVER['REQUEST_URI']);
			exit();
		}
	}
}

Base::instance()->init();
