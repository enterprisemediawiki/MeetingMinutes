<?php
/** 
 * The MeetingMinutes extension provides JS and CSS to enable recording meeting minutes in SMW. See README.md.
 * 
 * Documentation: https://github.com/enterprisemediawiki/MeetingMinutes
 * Support:       https://github.com/enterprisemediawiki/MeetingMinutes
 * Source code:   https://github.com/enterprisemediawiki/MeetingMinutes
 *
 * @file MeetingMinutes.php
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2014 by James Montalvo
 * @licence GNU GPL v3+
 */

# Not a valid entry point, skip unless MEDIAWIKI is defined
if (!defined('MEDIAWIKI')) {
	die( "MeetingMinutes extension" );
}

$GLOBALS['wgExtensionCredits']['parserhook'][] = array(
	'path'           => __FILE__,
	'name'           => 'MeetingMinutes',
	'url'            => 'http://github.com/enterprisemediawiki/MeetingMinutes',
	'author'         => 'James Montalvo',
	'descriptionmsg' => 'meetingminutes-desc',
	'version'        => '0.1.0'
);

# $dir: the directory of this file, e.g. something like:
#	1)	/var/www/wiki/extensions/BlankParserFunction
# 	2)	C:/xampp/htdocs/wiki/extensions/BlankParserFunction
// this isn't used, yet
// $dir = dirname( __FILE__ ) . '/';

# Location of "message file". Message files are used to store your extension's text
#	that will be displayed to users. This text is generally stored in a separate
#	file so it is easy to make text in English, German, Russian, etc, and users can
#	easily switch to the desired language.
// No internationalization yet
// $wgExtensionMessagesFiles['BlankParserFunction'] = $dir . 'BlankParserFunction.i18n.php';

# The "body" file will contain the bulk of a simple parser function extension. 
#	NEED MORE INFO HERE.
#
// No classes yet
// $wgAutoloadClasses['BlankParserFunction'] = $dir . 'BlankParserFunction.body.php';

# This specifies the function that will initialize the parser function.
#	NEED MORE INFO HERE.
#
// No parser function yet
// $wgHooks['ParserFirstCallInit'][] = 'BlankParserFunction::setup';



/**
 *  MeetingMinutes specific javascript and CSS modifications
 **/
$GLOBALS['wgHooks']['AjaxAddScript'][] = 'addMeetingMinutesFiles';
function addMeetingMinutesFiles ( $out ){
	global $wgScriptPath;

	$out->addScriptFile( $wgScriptPath .'/extensions/MeetingMinutes/lib/SF_MultipleInstanceRefire.js' );
	$out->addScriptFile( $wgScriptPath .'/extensions/MeetingMinutes/lib/meeting-minutes.js' );

	$out->addLink( array(
		'rel' => 'stylesheet',
		'type' => 'text/css',
		'media' => "screen",
		'href' => "$wgScriptPath/extensions/MeetingMinutes/lib/meeting-minutes.css"
	) );
	
	return true;
}
