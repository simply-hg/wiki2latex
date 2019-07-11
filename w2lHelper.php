<?php

/*
 * File: w2lHelper.php
 *
 * Purpose:
 * Provides all functions, which integrate into Mediawiki
 *
 * License:
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 */

if ( !defined('MEDIAWIKI') ) {
	$msg  = 'To install Wiki2LaTeX, put the following line in LocalSettings.php:<br/>';
	$msg .= '<tt>wfLoadExtension( "wiki2latex" );</tt>';
	echo $msg;
	exit( 1 );
}

require_once('w2lDefaultConfig.php');


if (!function_exists('wfLoadExtensionMessages')) {
	function wfLoadExtensionMessages($dontcare) { return; }
}

class Wiki2LaTeXHelper {
	static $messagesLoaded = false;
	static $actions = array('w2llatexform', 'w2ltexfiles', 'w2lpdf', 'w2ltextarea', 'w2lcleartempfolder');
	static $w2lLanguages = array(
		'Dutch'=>'dutch',
		'English' => 'english',
		'French' => 'french',
		'German' => 'german',
		'German (new)' => 'ngerman',
		'Hungarian'=>'hungarian',
		'Russian'=>'russian',
		'Ukranian'=>'ukrainian',
	);

	function __construct() {
		return true;       
	}

	static function Setup() {
		global $wgUser;

		// Check if messages are loaded. If not do so.
		if ( self::$messagesLoaded == false  ) {
			wfLoadExtensionMessages( 'wiki2latex' );
			self::$messagesLoaded = true;
		}

		if ( $wgUser->getOption('w2lDebug') == true ) {
			error_reporting(E_ALL);
		}
		return true;

	}

	static function onBeforePageDisplay(&$out) {
		$script = <<<EOF
<style type="text/css">/*<![CDATA[*/
li#ca-latex {margin-left:1.6em;}
div.w2l-debug {
	border:1px solid black;
	padding:1em;
}
/*]]>*/</style>
EOF;

		$out->addScript($script."\n");

		return true;

	}

	static public function onSkinTemplateNavigation(&$sktemplate, &$links) {

		global $wgUser;
		$title = $sktemplate->getSkin()->getTitle();

		if (self::$messagesLoaded == false ) {
			wfLoadExtensionMessages( 'wiki2latex' );
			self::$messagesLoaded = true;
		}
		$values = new webRequest();
		$action = $values->getVal('action');

		//$current_ns       = $wgTitle->getNamespace();
		$current_ns       = $title->getNamespace();
		$disallow_actions = array('edit', 'submit'); // disallowed actions

		if ( ($wgUser->getID() == 0) AND (Wiki2LaTeXConfig::$w2lConfig['allow_anonymous'] == false) ) {
			return true;
		}

		if ( ( in_array($current_ns, Wiki2LaTeXConfig::$w2lConfig['allowed_ns']) ) and !in_array($action, $disallow_actions)) {
			$links['views']['wiki2latex'] = array(
				'class' => ( in_array($action, self::$actions )  ) ? 'selected' : false,
				'text' => wfMessage('w2l_tab')->text(),
				'href' => $title->getLocalUrl( 'action=w2llatexform' )
			);
		}

		return true;
	}

	static public function onUnknownAction($action, $article) {
		global $wgUser;
		// Check the requested action
		// return if not for w2l
		//return true;

		$action = strtolower($action);
		if ( !in_array($action, self::$actions) ) {
			// Not our action, so return!
			return true;
		}

		// Check, if anonymous usage is allowed...
		if ( ($wgUser->getID() == 0) AND (Wiki2LaTeXConfig::$w2lConfig['allow_anonymous'] == false) ) {
			return true;
		}
		
		if ( !self::$messagesLoaded ) {
			onLoadAllMessages();
		}

		// we are on our own now!
		
		$w2l = new Wiki2LaTeXCore;
		$w2l->onUnknownAction($action, $article);
		return false;

	}
	

	static function onGetPreferences ( $user, &$preferences ) {
		wfLoadExtensionMessages( 'wiki2latex' );
		$preferences['w2lShowLog'] = array(
			'class' => 'HTMLCheckField',
			'label-message' => 'w2l_show_log', // a system message
			'section' => 'wiki2latex',
		);
		$preferences['w2lShowParsed'] = array(
			'class' => 'HTMLCheckField',
			'label-message' => 'w2l_show_parsed', // a system message
			'section' => 'wiki2latex',
		);
		$preferences['w2lDebug'] = array(
			'class' => 'HTMLCheckField',
			'label-message' => 'w2l_use_debug', // a system message
			'section' => 'wiki2latex',
		);
		$preferences['w2lBabelDefault'] = array(
			'class' => 'HTMLSelectField',
			'label-message' => 'w2l_babel', // a system message
			'section' => 'wiki2latex',
			'options' => self::$w2lLanguages,
			//
		);
		return true;
	}

}

class w2ltextareaAction extends Action {
	public function getName() {
		return "w2ltextarea";
	}

	public function show() {
		return Wiki2LaTeXHelper::onUnknownAction("w2ltextarea", $this->page);
	}
}

class w2llatexformAction extends Action {
	public function getName() {
		return "w2llatexform";
	}

	public function show() {
		return Wiki2LaTeXHelper::onUnknownAction("w2llatexform", $this->page);
	}
}

class w2ltexfilesAction extends Action {
	public function getName() {
		return "w2ltexfiles";
	}

	public function show() {
		return Wiki2LaTeXHelper::onUnknownAction("w2ltexfiles", $this->page);
	}
}

class w2lpdfAction extends Action {
	public function getName() {
		return "w2lpdf";
	}

	public function show() {
		return Wiki2LaTeXHelper::onUnknownAction("w2lpdf", $this->page);
	}
}

class w2lcleartempfolderAction extends Action {
	public function getName() {
		return "w2lcleartempfolder";
	}

	public function show() {
		return Wiki2LaTeXHelper::onUnknownAction("w2lcleartempfolder", $this->page);
	}
}
