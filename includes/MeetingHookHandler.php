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
		
		$title         = $params['title']->getValue();
		$day           = $params['day']->getValue();
		$time          = $params['time']->getValue();
		$building      = '[[' . $params['building']->getValue() . ']]';
		$room          = $params['room']->getValue();
		$phonenumber   = $params['phonenumber']->getValue();
		$phonepassword = $params['phonepassword']->getValue();
		$attendeesRaw  = explode( ',', $params['attendees']->getValue() );
		$overview      = $params['overview']->getValue();
		
		$attendees = array();
		foreach( $attendeesRaw as $attendee ) {
			$attendee = trim( $attendee );
			$attendees[] = "[[$attendee]]";
		}
		$attendees = implode( ', ', $attendees );
	
		// FIXME: this should be moved to a template (has MW decided on Handlebars?)
		// FIXME: maybe instead there should be an Infobox object to handle all infoboxes
		// FIXME: this obviously requires i18n
		$infobox = 
			"[[Category:Meeting]]".
			"<table class='meeting-minutes-infobox'>".
				"<caption>$title</caption>".
				"<tr><th class='meeting-minutes-infobox-row-label'>Day</th><td>$day</td></tr>".
				"<tr><th class='meeting-minutes-infobox-row-label'>Time</th><td>$time</td></tr>".
				"<tr><th class='meeting-minutes-infobox-row-label'>Building</th><td>$building</td></tr>".
				"<tr><th class='meeting-minutes-infobox-row-label'>Room</th><td>$room</td></tr>".
				"<tr><th class='meeting-minutes-infobox-row-label'>Conference phone #</th><td>$phonenumber</td></tr>".
				"<tr><th class='meeting-minutes-infobox-row-label'>Conference password</th><td>$phonepassword</td></tr>".
				"<tr><th class='meeting-minutes-infobox-row-label'>Significant attendees</th><td>$attendees</td></tr>".
			"</table>".
			"<p>$overview</p>".
			
			"<h2>Recent Meetings</h2>";
		
		
		$ask =
"{{#ask: [[Category:Meeting Minutes]] [[Meeting type::EVA Tools Panel]]
| ?Meeting date = Date
| ?Notes taken by
| sort = Meeting date
| order = desc
| limit = 10
| default = No meetings of this type have been added
}}";
		$ask = new \RawMessage( $ask );
		
		$html = $infobox . $ask->escaped() . "<p>[[Special:FormEdit/Meeting Minutes|Add Meeting Minutes]]</p>";
		
		return $html;
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