<?php
/**
 * <INSERT DESCRIPTION>.
 * 
 * Documentation: http://???
 * Support:       http://???
 * Source code:   http://???
 *
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2014 by James Montalvo
 * @licence GNU GPL v3+
 */

namespace MeetingMinutes;

use ParamProcessor\ProcessingResult;
use Parser;
use ParserHooks\HookHandler;
use SMWQueryProcessor;

class MeetingMinutesTopicHookHandler implements HookHandler {

	public function __construct(  ) {

	}

	/**
	 * @see HookHandler::handle
	 *
	 * @since 1.0
	 *
	 * @param Parser $parser
	 * @param ProcessingResult $result
	 *
	 * @return string
	 */
	public function handle( Parser $parser, ProcessingResult $result ) {
		if ( $result->hasFatal() ) {
			// TODO:
			return 'Invalid input. Cannot do something...';
		}
		
		global $wgOut; //FIXME: globals === bad, but I don't want this CSS added unless parser function called
		$wgOut->addModules( 'ext.meetingminutes.minutes' );
		
		$params = $result->getParameters();echo "handled MeetingMinutes Topic<br />\n";

		$meetingTopicModel = array(
			// FIXME: this obviously requires i18n for labels

			'topictitle'      => $params['topic title']->getValue(),
			'relatedarticles' => $params['related articles']->getValue(),
			'topictext'       => $params['topic text']->getValue(),
		);

		// $attendeesRaw  = explode( ',', $params['attendees']->getValue() );
		// $attendees = array();
		// foreach( $attendeesRaw as $attendee ) {
			// $attendee = trim( $attendee );
			// $attendees[] = "[[$attendee]]";
		// }
		// $meetingModel[ 'attendees' ] = implode( ', ', $attendees );
	
		
		// FIXME: maybe there should be a special Infobox class/template to
		// handle all infoboxes
		$meetingTopicView = new View ( 'minutes.topic.mustache' );
		
		// $minutesForMeeting = new AskView ( 'minutesbymeeting.mustache' );
		// $meetingModel[ 'minutesaskquery' ] = $minutesForMeeting->render( array( 'meetingtype' => 'EVA Tools Panel' ) );
		
		return $meetingTopicView->render( $meetingTopicModel );

	}







	/*
	static function renderParserFunction ( &$parser, $frame, $args ) {

		$args = self::processArgs( $frame, $args, array("", 255, 1) );
			
		$full_text  = $args[0];
		$max_length = $args[1];
		$max_lines  = $args[2];
		
		$needle = "\n";
		for($i=0; $i<$max_lines; $i++) {
			if ($newline_pos)
				$offset = $newline_pos + strlen($needle);
			else
				$offset = 0;
			$newline_pos = strpos($full_text, $needle, $offset);
		}

		if ($newline_pos) {
			// trim to specified number of newlines
			$synopsis = substr($full_text, 0, $newline_pos);
		}
		else {
			$synopsis = $full_text;
		}
		
		// trim at max characters
		if (strlen($synopsis) > $max_length) {
			$synopsis = substr($synopsis, 0, $max_length);
			$last_space = strrpos($synopsis, ' ');
			$synopsis = substr($synopsis, 0, $last_space) . ' ...';
		}

		return $synopsis;
	}
	
	static function processArgs( $frame, $args, $defaults ) {
		$new_args = array();
		$num_args = count($args);
		$num_defaults = count($defaults);
		$count = ($num_args > $num_defaults) ? $num_args : $num_defaults;
		
		for ($i=0; $i<$count; $i++) {
			if ( isset($args[$i]) )
				$new_args[$i] = trim( $frame->expand($args[$i]) );
			else
				$new_args[$i] = $defaults[$i];
		}
		return $new_args;
	}*/

}