<?php
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 */
 
 if ( !defined('MEDIAWIKI') ) {
	$msg  = 'To install Wiki2LaTeX, put the following line in LocalSettings.php:<br/>';
	$msg .= '<tt>wfLoadExtension( "wiki2latex" );</tt>';
	echo $msg;
	exit( 1 );
}
 
// This file adds support for ParserFunctions as defined by the ParserFunctions-Extension.
// Some functions are not yet supported by w2l...

$w2lExtParserFunctions = new ExtParserFunctions;
Wiki2LaTeXConfig::$w2lParserFunctions['expr']    = array( &$w2lExtParserFunctions, 'expr' );
Wiki2LaTeXConfig::$w2lParserFunctions['if']      = array( &$w2lExtParserFunctions, 'ifHook' );
Wiki2LaTeXConfig::$w2lParserFunctions['switch']  = array( &$w2lExtParserFunctions, 'switchHook' );
Wiki2LaTeXConfig::$w2lParserFunctions['ifeq']    = array( &$w2lExtParserFunctions, 'ifeq' );
Wiki2LaTeXConfig::$w2lParserFunctions['ifexpr']  = array( &$w2lExtParserFunctions, 'ifexpr' );
Wiki2LaTeXConfig::$w2lParserFunctions['ifexist'] = array( &$w2lExtParserFunctions, 'time' );
Wiki2LaTeXConfig::$w2lParserFunctions['rel2abs'] = array( &$w2lExtParserFunctions, 'rel2abs' );
Wiki2LaTeXConfig::$w2lParserFunctions['time']    = array( &$w2lExtParserFunctions, 'time' );

