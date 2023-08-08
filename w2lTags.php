<?php

/*
 * File:    w2lTags.php
 * Created: 2007-09-01
 * Version: 0.9
 *
 * Purpose:
 * Provides some xml-style Parser-Extension-Tags to Mediawiki and W2L
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

if ( !defined('MEDIAWIKI') )
	die();

/* W2l-Parser-Extensions */

class Wiki2LaTeXTags {
	static $w2lTags = array();

	static function Setup(&$parser) {
		// Register Extension-Tags to Mediawiki...
		
		// LaTeX-commands, which we want to offer to a wiki-article
		$parser->setHook("noindent",    "Wiki2LaTeXTags::NoIndent");
		$parser->setHook("newpage",     "Wiki2LaTeXTags::NewPage");
		$parser->setHook("label",       "Wiki2LaTeXTags::Label");
		$parser->setHook("pageref",     "Wiki2LaTeXTags::PageRef");
		$parser->setHook("chapref",     "Wiki2LaTeXTags::ChapRef");
		
		// Extension-tags, which we need for w2l:
		$parser->setHook("templatevar", "Wiki2LaTeXTags::TemplateVar");
		$parser->setHook("latex",       "Wiki2LaTeXTags::Latex");
		$parser->setHook("latexpage",   "Wiki2LaTeXTags::LatexPage");
		$parser->setHook("latexfile",   "Wiki2LaTeXTags::LatexFile");
		
		// By this one, you can directly input latex to a wiki article. LaTeX code is not interpreted in wiki-mode, though.
		$parser->setHook("rawtex",      "Wiki2LaTeXTags::RawTex");

		return true;
	}
	
	static function w2lSetup($parser) {
		Wiki2LaTeXTags::$w2lTags['rawtex'] = "Wiki2LaTeXTags::rawtex";
		
		// Some Extensions, which return LaTeX-commands
		Wiki2LaTeXTags::$w2lTags['newpage'] = "Wiki2LaTeXTags::NewPage";
		Wiki2LaTeXTags::$w2lTags['noindent'] = "Wiki2LaTeXTags::NoIndent";
		Wiki2LaTeXTags::$w2lTags['label']    = "Wiki2LaTeXTags::Label";
		Wiki2LaTeXTags::$w2lTags['pageref']  = "Wiki2LaTeXTags::PageRef";
		Wiki2LaTeXTags::$w2lTags['chapref']  = "Wiki2LaTeXTags::ChapRef";
		
		// These Tags should not return a value in LaTeX-Mode.
		Wiki2LaTeXTags::$w2lTags['templatevar'] = "Wiki2LaTeXTags::EmptyTag";
		Wiki2LaTeXTags::$w2lTags['latexpage']   = "Wiki2LaTeXTags::EmptyTag";
		Wiki2LaTeXTags::$w2lTags['latex']       = "Wiki2LaTeXTags::EmptyTag";
		
		return true;
	}

	static function Latex($input, $argv, $parser, $frame, $mode = 'wiki') {
		$output = '<pre style="overflow:auto;">'.trim($input)."</pre>";

		return $output;
	}

	static function LatexPage($input, $argv, $parser, $frame, $mode = 'wiki') {

		switch ($mode) {
			case 'wiki':
				$output = "<p><strong>Benoetigte Datei</strong>: ".$input."</p>";
				$output = "'''Ben&ouml;tigte Datei''': [[".$input."]]";
				$output = $parser->recursiveTagParse($output);

			break;
			case 'latex': $output = '';
			break;
			default: $output = $mode;
		}
		return $output;
	}

	static function LatexFile($input, $argv, $parser, $frame, $mode = 'wiki') {
		$output  = '<strong>Dateiname:</strong> '.$argv['name'].'.tex';
		$output .= '<pre style="overflow:auto;">'.trim($input)."</pre>";

		return $output;
	}

	static function TemplateVar($input, $argv, $parser, $frame, $mode = 'wiki') {
		$output = "<p><strong>".$argv['vname']."</strong>: ".$input."</p>";
		return $output;
	}

	// Latex-commands for Mediawiki
	static function NoIndent($input, $argv, $parser, $frame, $mode = 'wiki') {
		switch($mode) {
			case 'latex':
				return '{\noindent}';
			default:
				return "";
		}
	}

	static function NewPage($input, $argv, $parser, $frame, $mode = 'wiki') {
		switch($mode) {
			case 'latex':
				return '\clearpage{}';
			default:
				return '<hr/>';
		}
	}

	static function Label($input, $argv, $parser, $frame, $mode = 'wiki') {
		switch($mode) {
			case 'latex':
				$output = '\label{'.$input.'}';
			break;
			default:
				$output = ' <strong>(Anker: '.$input.'<a id="'.$input.'"></a>)</strong>';
			break;
		}
		return $output;
	}

	static function PageRef($input, $argv, $parser, $frame, $mode = 'wiki') {
		switch($mode) {
			case 'latex':
				$output = '\page{'.$input.'}';
			break;
			default:
				$output = '<a href="#'.$input.'" title="'.$input.'">S. XX</a>';
			break;
		}
		return $output;
	}

	static function ChapRef($input, $argv, $parser, $frame, $mode = 'wiki') {
		switch($mode) {
			case 'latex':
				$output = '\ref{'.$input.'}';
			break;
			default:
				$output = '<a href="#'.$input.'" title="'.$input.'">X.Y</a>';
			break;
		}
		return $output;
	}

	static function rawtex($input, $argv, $parser, $frame, $mode = 'wiki') {
		switch ($mode) {
			case 'latex': 
				$output = $input;
			break;
			case 'wiki':
				$input  = trim($input);
	          		$output = '<pre style="overflow:auto;">'.$input."</pre>";
			break;
			default: $output = $input;
			break;
		}
		return $output;
	}

	function EmptyTag($input, $argv, $parser, $frame, $mode = 'wiki') {
		if ( $mode == 'latex' ) {
			return '';
		} else {
			return $input;
		}
	}
	
}
