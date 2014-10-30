<?php
/**
 * <INSERT DESCRIPTION>.
 * 
 * Documentation: http://???
 * Support:       http://???
 * Source code:   http://???
 *
 * @file MinutesParserFunctions.php
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2014 by James Montalvo
 * @licence GNU GPL v3+
 */

namespace MeetingMinutes;

class ParserFunction {

	static function renderParserFunction ( &$parser, $frame, $args ) {

		
	}
	
	/**
	 * Converts an array of values in form [0] => "name=value" into a real
	 * associative array in form [name] => value
	 *
	 * @param array string $options
	 * @return array $results
	 */
	static function extractOptions( $frame, array $args ) {
		
		// set default options
		$options = array(
			'example' => 1
		);

		
		$tempTasks = array();
		$tasks = array();
		$taskDetails = array();
		$tasksDurationPercentTotal = array();
		$tasksDurationPercentTotal['actor1'] = 0;
		$tasksDurationPercentTotal['actor2'] = 0;
		$tasksDurationPercentTotal['actor3'] = 0;

		
		foreach ( $args as $arg ) {
			//Convert args with "=" into an array of options
			$pair = explode( '=', $frame->expand($arg) , 2 );
			if ( count( $pair ) == 2 ) {
				$name = strtolower(trim( $pair[0] )); //Convert to lower case so it is case-insensitive
				$value = trim( $pair[1] );

				//this switch could be consolidated
				switch ($name) {
					case 'format': 
						$value = strtolower($value);
						if ( $value=="full" ) {
				        	$options[$name] = "full";
				        } else {
				        	$options[$name] = "compact";
				        }
				        break;
					case 'fixedwidth': 
						if ( $value != "" ) {
				        	$options[$name] = $value;
				        }
				        break;
			        case 'st index':
				        $options[$name] = $value;
				        break;
				    case 'title':
					    if ( !isset($value) || $value=="" ) {
				        	$options['title']= "No title set!";
				        } else {
				        	$titleParts = explode( '@@@', $value);
				        	$options[$name] = $titleParts[0];
				        	$options['title link'] = $titleParts[1];
				        }
				        break;
				    case 'eva title':
					    if ( isset($value) && $value!="" ) {
				        	$options[$name] = $value;
				        }
				        break;
			        case 'depends on':
				        if ( isset($value) ) {
				        	$options[$name] = $value;
				        }
				        break;
			        case 'hardware required for eva':
				        $i = 1; /* Task id */
					    if( isset($value) && $value!="" ){
						    $tempHardware = explode ( '&&&', $value, 2 );
						    $hardware = explode ( '&&&', $tempHardware[1] );
						    foreach ( $hardware as $item ) {
						    	$itemDetails = explode( '@@@', $item);
						    	$options['hardware required for eva'][$i]['title'] = trim($itemDetails[0]);
						    	$options['hardware required for eva'][$i]['mission'] = trim($itemDetails[1]);
						    	$i++;
						    }
						}
				        break;
			        case 'parent related article':
				        if ( isset($value) ) {
				        	$options[$name] = $value;
				        }
				        break;
				    case 'eva duration hours':
				    	$options[$name] = $value;
				    	$options['eva duration in minutes'] += (60 * $value);
				        break;
				    case 'eva duration minutes':
				    	$options[$name] = $value;
				    	$options['eva duration in minutes'] += $value;
				        break;
			        case 'actor 1 name':
				        if ( isset($value) &&  $value != "" ) {
				        	$options[$name] = $value;
				        	$options['rows']['actor1']['name'] = $value;
				        } else {
				        	$options[$name] = 'Actor 1';
				        	$options['rows']['actor1']['name'] = 'Actor 1';
				        }
				        break;
			        case 'actor 2 name':
				        if ( isset($value) &&  $value != "" ) {
				        	$options[$name] = $value;
				        	$options['rows']['actor2']['name'] = $value;
				        } else {
				        	$options[$name] = 'Actor 2';
				        	$options['rows']['actor2']['name'] = 'Actor 2';
				        }
				        break;
			        case 'actor 3 name':
				        if ( isset($value) &&  $value != "" ) {
				        	$options[$name] = $value;
				        	$options['rows']['actor3']['name'] = $value;
				        } else {
				        	$options[$name] = 'Actor 3';
				        	$options['rows']['actor3']['name'] = 'Actor 3';
				        }
				        break;
			        case 'actor 1 display in compact view':
				        if ( isset($value) ) {
				        	$options[$name] = $value;
				        	$options['rows']['actor1']['display in compact view'] = $value;
				        }
				        break;
			        case 'actor 2 display in compact view':
				        if ( isset($value) ) {
				        	$options[$name] = $value;
				        	$options['rows']['actor2']['display in compact view'] = $value;
				        }
				        break;
			        case 'actor 3 display in compact view':
				        if ( isset($value) ) {
				        	$options[$name] = $value;
				        	$options['rows']['actor3']['display in compact view'] = $value;
				        }
				        break;
			        case 'actor 1 enable get aheads':
				        if ( isset($value) ) {
				        	$options[$name] = $value;
				        	$options['rows']['actor1']['enable get aheads'] = $value;
				        }
				        break;
			        case 'actor 2 enable get aheads':
				        if ( isset($value) ) {
				        	$options[$name] = $value;
				        	$options['rows']['actor2']['enable get aheads'] = $value;
				        }
				        break;
			        case 'actor 3 enable get aheads':
				        if ( isset($value) ) {
				        	$options[$name] = $value;
				        	$options['rows']['actor3']['enable get aheads'] = $value;
				        }
				        break;
				    case 'actor1': // NEED TO SPLIT OUT SO THIS DOESN'T HAVE GET-AHEADS ADDED
					    // this should have blocks with "Start time" (not duration)
					    // an option should be included to sync with a task on EV1 and/or EV2
					    // break;
				    case 'actor2':
				    case 'actor3':
					    $i = 1; /* Task id */
						$tasksDuration = 0;
					    if( isset($value) && $value!="" ){
						    $tempTasks = explode ( '&&&', $value, 2 );
						    $tasks = explode ( '&&&', $tempTasks[1] );
						    
						    foreach ( $tasks as $task ) {
						    	$taskDetails = explode( '@@@', $task);
						    	$options['rows'][$name]['tasks'][$i]['title'] = $taskDetails[0];
						    	if ($taskDetails[1] == ''){$taskDetails[1] = '0';}
						    	$options['rows'][$name]['tasks'][$i]['durationHour'] = $taskDetails[1];
						    	if ($taskDetails[2] == ''|'0'){$taskDetails[2] = '00';}
						    	if ( strlen($taskDetails[2]) == 1 ){
						    		$temp = $taskDetails[2];
						    		$taskDetails[2] = '0' . $temp;}
						    	$options['rows'][$name]['tasks'][$i]['durationMinute'] = $taskDetails[2];
						    	//Lame attempt to set min block width - move value out?
						    	// if ($options['rows'][$name]['tasks'][$i]['durationHour'] == 0 && $options['rows'][$name]['tasks'][$i]['durationMinute']<15){
						    	// 	$options['rows'][$name]['tasks'][$i]['blockWidth'] = 15;
						    	// }
						    	$options['rows'][$name]['tasks'][$i]['relatedArticles'] = $taskDetails[3];
						    	$options['rows'][$name]['tasks'][$i]['color'] = $taskDetails[4];
						    	$options['rows'][$name]['tasks'][$i]['details'] = trim($taskDetails[5]);

						    	// Calc task duration as % of total EVA duration
						    	$options['rows'][$name]['tasks'][$i]['durationPercent'] = round((((60 * $taskDetails[1]) + $taskDetails[2]) / $options['eva duration in minutes']) * 100);

						    	// append task duration
						    	$tasksDuration += (60 * $taskDetails[1]) + $taskDetails[2];
						    	// append task duration percent
						    	$tasksDurationPercentTotal[$name] += $options['rows'][$name]['tasks'][$i]['durationPercent'];
						    	// print_r( $tasksDurationPercentTotal['ev1'] );
						    	$i++;
						    }
						}

					    // NEED TO ADD EGRESS/INGRESS DURATION TO $tasksDuration
					    // NEED TO ACCOUNT FOR EV1 vs EV2

					    // Commented out due to new template structure including egress/ingress as tasks
					    // $tasksDuration += $options['ev2 egress duration minutes']['durationMinutes'] + $options['ev2 ingress duration minutes']['durationMinutes'];

					    // sum of time allotted to tasks
					    $options['rows'][$name]['tasksDuration'] = $tasksDuration;

					    // $options[$name] = self::extractTasks( $value );

					    // Check if $tasksDuration < $options['duration'] (EVA duration)
					    if( $options['rows'][$name]['enable get aheads']=='true' && $tasksDuration < $options['eva duration in minutes'] ){
					    	// Need to add "Get Aheads" block to fill timeline gap

					    	// Calculate difference between EVA duration and tasksDuration
					    	$timeLeft = $options['eva duration in minutes'] - $tasksDuration;
					    	$timeLeftHours = floor($timeLeft/60);
					    	$timeLeftMinutes = $timeLeft%60;

							// THE FOLLOWING MOVES GET-AHEADS TO SECOND-TO-LAST SPOT
					    	$options['rows'][$name]['tasks'][$i]['title'] = $options['rows'][$name]['tasks'][$i-1]['title'];
					    	$options['rows'][$name]['tasks'][$i]['durationHour'] = $options['rows'][$name]['tasks'][$i-1]['durationHour'];
					    	$options['rows'][$name]['tasks'][$i]['durationMinute'] = $options['rows'][$name]['tasks'][$i-1]['durationMinute'];
					    	$options['rows'][$name]['tasks'][$i]['relatedArticles'] = $options['rows'][$name]['tasks'][$i-1]['relatedArticles'];
					    	$options['rows'][$name]['tasks'][$i]['color'] = $options['rows'][$name]['tasks'][$i-1]['color'];
					    	$options['rows'][$name]['tasks'][$i]['details'] = trim($options['rows'][$name]['tasks'][$i-1]['details']);

					    	// Now set Get-Aheads block data
					    	$options['rows'][$name]['tasks'][$i-1]['title'] = 'Get-Aheads';
						    	if ($timeLeftHours == ''){$timeLeftHours = '0';}
					    	$options['rows'][$name]['tasks'][$i-1]['durationHour'] = $timeLeftHours;
						    	if ($timeLeftMinutes == ''|'0'){$timeLeftMinutes = '00';}
						    	if ( strlen($timeLeftMinutes) == 1 ){
						    		$temp = $timeLeftMinutes;
						    		$timeLeftMinutes = '0' . $temp;}
					    	$options['rows'][$name]['tasks'][$i-1]['durationMinute'] = $timeLeftMinutes;
					    	$options['rows'][$name]['tasks'][$i-1]['relatedArticles'] = 'Get-Ahead Task';
					    	$options['rows'][$name]['tasks'][$i-1]['color'] = 'white';
					    	$options['rows'][$name]['tasks'][$i-1]['details'] = 'Auto-generated block based on total EVA duration and sum of task durations';
					    	// Calc task duration as % of total EVA duration
					    	// $options['rows'][$name]['tasks'][$i]['durationPercent'] = round((((60 * $timeLeftHours) + $timeLeftMinutes) / $options['eva duration in minutes']) * 100);
							$options['rows'][$name]['tasks'][$i-1]['durationPercent'] = 100 - $tasksDurationPercentTotal[$name];

					    }

				        break;
			        case 'color white meaning':
			        case 'color red meaning':
			        case 'color orange meaning':
			        case 'color yellow meaning':
			        case 'color blue meaning':
			        case 'color green meaning':
			        case 'color pink meaning':
			        case 'color purple meaning':
				        if ( isset($value) && $value!="" ) {
				        	$options[$name] = $value;
				        	$options['number of colors designated'] ++;
				        }
				        break;
			        case 'ev1':
				        // Unique things for this column? Would have to split above into these two (can't do both cases)
				        break;
			        case 'ev2':
				        // Unique things for this column?
				        break;
			        default: //What to do with args not defined above
				}

			}

		}

		return $options;
	}
	
}