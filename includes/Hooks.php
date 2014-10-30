<?php
/**
 * <INSERT DESCRIPTION>.
 * 
 * Documentation: http://???
 * Support:       http://???
 * Source code:   http://???
 *
 * @file FilesParserFunctions.php
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2014 by James Montalvo
 * @licence GNU GPL v3+
 */

namespace MeetingMinutes;

class Hooks {

	static function setupParserFunctions ( &$parser ) {
		
		// set the {{#meetingminutes: ... }} parser function
		$parser->setFunctionHook(
			'meetingminutes',
			array(
				'MeetingMinutes\MinutesParserFunction',
				'renderParserFunction'
			),
			SFH_OBJECT_ARGS
		);

		// set the {{#synopsize: ... }} parser function
		$parser->setFunctionHook(
			'synopsize',
			array(
				'MeetingMinutes\SynopsizeParserFunction',
				'renderParserFunction'
			),
			SFH_OBJECT_ARGS // defines the format of how data is passed to your function...don't worry about it for now.
		);

		
		return true;
		
	}
	


	/**
	* Handler for BeforePageDisplay hook.
	* @see http://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	* @param $out OutputPage object
	* @param $skin Skin being used.
	* @return bool true in all cases
	*/
	static function onBeforePageDisplay( $out, $skin ) {
		$out->addModules( array( 'ext.meetingminutes.form' ) );
	}

}