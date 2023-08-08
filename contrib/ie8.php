<?php

$wgHooks['w2lFormFieldsets'][]               = 'w2lCompatibilityIE8';

function w2lCompatibilityIE8(&$parser, &$fieldsets) {

	$export_options['legend'] = wfMessage('w2l_select_output')->text();
	$export_options['html'] = '<input type="submit" name="action" value="w2ltextarea" />'.wfMessage('w2l_select_textarea')->text().'<br/>';
	$export_options['html'] .= '<input type="submit" name="action" value="w2ltexfiles" />'.wfMessage('w2l_select_texfiles')->text().'<br/>';

	if ( true == $parser->config['pdfexport'] ) {
		$export_options['html'] .= '<input type="submit" name="action" value="w2lpdf" />';
		$export_options['html'] .= wfMessage('w2l_select_pdf','pdf')->text();
		$export_options['html'] .= '<br/>';
	}
	
	$fieldsets[10] = $export_options;
	$fieldsets[1000] = str_replace('checked="checked"', '',$export_options);
	return true;
}
