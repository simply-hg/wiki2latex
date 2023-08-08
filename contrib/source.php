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
 
if ( !function_exists('w2lSource') ) {

	Wiki2LaTeXTags::$w2lTags['source'] = 'w2lSource';

	function w2lSource($input, $argv, $parser, $frame = false, $mode = 'latex') {
		$parser->addPackageDependency('listings');

		$language = $argv['lang'];
		$output   = "\lstset{language=$language}\n\\begin{lstlisting}\n";
		$output  .= $input;
		$output  .= "\\end{lstlisting}\n";

		return $output;
	}
}
