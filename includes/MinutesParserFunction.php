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

class MinutesParserFunction {

	static function renderParserFunction ( &$parser, $frame, $args ) {
		
		// first argument
		$file = trim( $frame->expand($args[0]) );

		// check for second argument
		if ( count($args) > 1 )
			$altText = trim( $frame->expand($args[1]) );
		else
			$altText = "";
		
		return "File = $file, Alt = $altText";
		
	}
	/*
	static function formatHttpFile ( $file, $altText='' ) {
		if ( $altText == '' ) {
			$maxLength = 50;
			$altText = preg_replace( "/^http[s]*:\/\//i", "", $file );
			if ( strlen( $altText ) > $maxLength ) {
				$altText = substr( $altText, 0, $maxLength-3 ) . '...';
			}
		}
		
		return "[$file $altText]";
	}
	
	static function formatFileSystemFile ( $file, $altText='' ) {
		return "<code>$file</code>";
	}

	static function formatWikiFile ( $file, $altText='' ) {
				
		// if starts with "File:" strip it for file name
		if ( preg_match( "/^File:/i", $file ) ) {
			$fileNameOnly = substr( $file, 5 );
			$fileWithPrefix = $file;
		}
		else {
			$fileNameOnly = $file;
			$fileWithPrefix = 'File:' . $file;
		}

		if ( $altText == '' ) {
			$altText = $fileNameOnly;
		}		

		if ( \Title::makeTitle( NS_FILE, $fileNameOnly )->exists() ) {
			
			return "[[Media:$fileNameOnly|$altText]] <sup>&#91;[[:$fileWithPrefix|file info]]&#93;</sup>";
		
		}
		else {
			return "[[$fileWithPrefix]]";
		}
		
	}
	*/
	
}