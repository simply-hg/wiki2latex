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

use MediaWiki\MediaWikiServices;

$w2lExtStringFunctions = new ExtStringFunctions ();

Wiki2LaTeXConfig::$w2lParserFunctions['len']     = array(&$w2lExtStringFunctions, 'runLen' );
Wiki2LaTeXConfig::$w2lParserFunctions['pos']     = array(&$w2lExtStringFunctions, 'runPos');
Wiki2LaTeXConfig::$w2lParserFunctions['rpos']    = array(&$w2lExtStringFunctions, 'runRPos');
Wiki2LaTeXConfig::$w2lParserFunctions['sub']     = array(&$w2lExtStringFunctions, 'runSub');
Wiki2LaTeXConfig::$w2lParserFunctions['pad']     = array(&$w2lExtStringFunctions, 'runPad');
Wiki2LaTeXConfig::$w2lParserFunctions['replace'] = array(&$w2lExtStringFunctions, 'runReplace');
Wiki2LaTeXConfig::$w2lParserFunctions['explode'] = array(&$w2lExtStringFunctions, 'runExplode');

/* Leaving them out this time...
    MediaWikiServices::getInstance()->getParser()->setFunctionHook('urlencode',array(&$wgExtStringFunctions,'runUrlEncode'));
    MediaWikiServices::getInstance()->getParser()->setFunctionHook('urldecode',array(&$wgExtStringFunctions,'runUrlDecode'));
*/
