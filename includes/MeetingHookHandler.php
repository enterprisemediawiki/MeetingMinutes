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

class MeetingHookHandler implements HookHandler {

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
		
		
		$params = $result->getParameters();

		$meetingModel = array(
			// FIXME: this obviously requires i18n for labels

			'title'         => $params['title']->getValue(),
			'day'           => $params['day']->getValue(),
			'time'          => $params['time']->getValue(),
			'building'      => '[[' . $params['building']->getValue() . ']]',
			'room'          => $params['room']->getValue(),
			'phonenumber'   => $params['phone number']->getValue(),
			'phonepassword' => $params['phone password']->getValue(),
			'overview'      => $params['overview']->getValue(),
		);

		$attendeesRaw  = explode( ',', $params['attendees']->getValue() );
		$attendees = array();
		foreach( $attendeesRaw as $attendee ) {
			$attendee = trim( $attendee );
			$attendees[] = "[[$attendee]]";
		}
		$meetingModel[ 'attendees' ] = implode( ', ', $attendees );
	
		
		// FIXME: maybe there should be a special Infobox class/template to
		// handle all infoboxes
		$meetingView = new View ( 'meeting.mustache' );
		$minutesForMeeting = new AskView ( 'minutesbymeeting.mustache' );

		$meetingModel[ 'minutesaskquery' ] = $minutesForMeeting->render( array( 'meetingtype' => 'EVA Tools Panel' ) );
		
		return $meetingView->render( $meetingModel );

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