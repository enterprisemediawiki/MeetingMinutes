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

class MeetingMinutesHookHandler implements HookHandler {

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

		$meetingMinutesModel = array(
			// FIXME: this obviously requires i18n for labels			
			'meetingtype'        => $params['meeting type']->getValue(),
			'date'               => $params['date']->getValue(),
			'start time hour'    => $params['start time hour']->getValue(),
			'start time minute'  => $params['start time minute']->getValue(),
			'notes taken by'     => $params['notes taken by']->getValue(),
			'topics'             => $params['topics']->getValue(),
		);

		// $attendeesRaw  = explode( ',', $params['Attendees']->getValue() );
		// $attendees = array();
		// foreach( $attendeesRaw as $attendee ) {
			// $attendee = trim( $attendee );
			// $attendees[] = "[[$attendee]]";
		// }
		// $meetingModel[ 'attendees' ] = implode( ', ', $attendees );
	
		
		// FIXME: maybe there should be a special Infobox class/template to
		// handle all infoboxes
		$meetingMinutesView = new View ( 'minutes.mustache' );
				
		#return $meetingMinutesView->render( $meetingMinutesModel );
		$out = $meetingMinutesView->render( $meetingMinutesModel );
		return $out;
		// global $wgOut;
		// $output = $wgOut->parse( $out );

		// return array( $output, 'noparse' => true, 'isHTML' => true );
		
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