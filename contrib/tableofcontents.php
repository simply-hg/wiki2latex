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
 
$wgHooks['w2lMagicTemplateOptions'][] = 'w2lTocForm';
$wgHooks['w2lFinish'][]               = 'w2lTocHook';
$wgHooks['w2lRegisterOptions'][]      = 'w2lToc';

function w2lToc($core) {
	$core->addParserParameter('make_toc');
	return true;
}

function w2lTocHook( $parser, &$text) {

	if ( $parser->getVal('make_toc') ) {
		$text = '\tableofcontents'."\n\n".$text;
	}
	return true;
}

function w2lTocForm( $core, &$output ) { 
	$output .= '<label><input type="checkbox" name="make_toc" value="true" /> ';
	//$output .= wfMessage('w2l_select_avant')->text().'</label><br />'."\n";
	$output .= ' Add a table of content </label><br />'."\n";
	return true;
}


