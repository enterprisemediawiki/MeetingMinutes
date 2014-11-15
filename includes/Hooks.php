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
		// $parser->setFunctionHook(
			// 'meetingminutes',
			// array(
				// 'MeetingMinutes\MinutesParserFunction',
				// 'renderParserFunction'
			// ),
			// SFH_OBJECT_ARGS
		// );

		// set the {{#synopsize: ... }} parser function
		// $parser->setFunctionHook(
			// 'synopsize',
			// array(
				// 'MeetingMinutes\SynopsizeParserFunction',
				// 'renderParserFunction'
			// ),
			// SFH_OBJECT_ARGS // defines the format of how data is passed to your function...don't worry about it for now.
		// );

		$hookRegistrant = new \ParserHooks\HookRegistrant( $parser );

		$hookRegistrant->registerFunctionHandler(
			new \ParserHooks\HookDefinition(
				'synopsize',
				array(
					'synopsis' => array(
						'default' => 'a synopsis',
						'message' => 'synopsize-parameter-synopsis'
					),
					'second' => array(
						'default' => 'second',
						'message' => 'synopsize-parameter-second'
					)
				),
				'synopsis'
			),
			new SynopsizeHookHandler()
		);
		
		
		$extension = new \MeetingMinutes\Extension( \MeetingMinutes\Settings::newFromGlobals( $GLOBALS ) );

		$hookRegistrant->registerFunctionHandler(
			$extension->getMeetingHookDefinition(),
			new MeetingHookHandler()
		);

		$hookRegistrant->registerFunctionHandler(
			$extension->getMeetingMinutesHookDefinition(),
			new MeetingMinutesHookHandler()
		);

		$hookRegistrant->registerFunctionHandler(
			$extension->getMeetingMinutesTopicHookDefinition(),
			new MeetingMinutesTopicHookHandler()
		);
		
		return true;
		
	}
	
	
// $GLOBALS['wgHooks']['ParserFirstCallInit'][] = function( Parser &$parser ) {



	// $hookRegistrant->registerFunctionHandler(
		// $extension->getListHookDefinition(),
		// $extension->getListHookHandler()
	// );

	// $hookRegistrant->registerHookHandler(
		// $extension->getCountHookDefinition(),
		// $extension->getCountHookHandler()
	// );

	// $hookRegistrant->registerHookHandler(
		// $extension->getListHookDefinition(),
		// $extension->getListHookHandler()
	// );

	// return true;
// };

	
	


	/**
	* Handler for BeforePageDisplay hook.
	* @see http://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	* @param $out OutputPage object
	* @param $skin Skin being used.
	* @return bool true in all cases
	*/
	static function onBeforePageDisplay( $out, $skin ) {
		$out->addModules( array( 'ext.meetingminutes.base', 'ext.meetingminutes.form' ) );
	}
	
	
	/**
	* Handler for smwInitProperties hook.
	* @return bool true in all cases
	*/
	static function onSmwInitProperties () {

		// id, typeid, label, show
		// \SMW\DIProperty::registerProperty(
			// '___somenewproperty',
			// 2,
			// 'meetingminutes-some-property',
			// true
		// );
		
		
		/**
		 * @note Property data types as follows (this needs to go somewhere else)
		 * From SMWDataItem:
		 *   0  = No data item class (not sure if this can be used)
		 *   1  = Number
		 *   2  = String/Text
		 *   3  = Blob
		 *   4  = Boolean
		 *   5  = URI
		 *   6  = Time (This must mean Date)
		 *   7  = Geo
		 *   8  = Container
		 *   9  = WikiPage
		 *   10 = Concept
		 *   11 = Property
		 *   12 = Error
		 */
		return MeetingPropertyRegistry::getInstance()->registerPropertiesAndAliases();
		
	}

	/**
	* Actually updates SMW properties on the page.
	* @return bool true in all cases
	*/
	public static function onSMWStoreUpdateDataBefore ( \SMW\Store $store, \SMW\SemanticData $semanticData ) {
	
		$propertyDI = new \SMW\DIProperty( '___MEETINGSTANDARDDAY' );
		$dataItem = new \SMWDIString( "Fakeday" ); // e.g. new DINumber ( num val ); or DIWikiPage::newFromTitle( titleobject ); or ...
		
		$semanticData->addPropertyObjectValue( $propertyDI, $dataItem );
		
		return true;
	}
	
	
	
	/**
	* Handler for smwInitDatatypes hook.
	* @return bool true in all cases
	*/
	// static function onSmwInitDatatypes () {

		// // id, typeid, label, show
		// \SMW\DIProperty::registerPropertyAlias(
			// '___somenewproperty',
			// 'Some property'
		// );

		// return true;
	// }


}

class MeetingPropertyRegistry extends \SESP\PropertyRegistry {

	/**
	 * @note Overriding \SESP\PropertyResgistry to insert a Definitionreader
	 * with MeetingMinutes property definitions.
	 *
	 * @since FIXME
	 *
	 * @return PropertyRegistry
	 */
	public static function getInstance() {
		if ( self::$instance === null ) {
		
			$sespDefinitionReader = new \SESP\Definition\DefinitionReader( __DIR__ . '/Definitions/properties.json' );

			self::$instance = new self(
				$sespDefinitionReader,
				new \SESP\Cache\MessageCache( $GLOBALS['wgContLang'] )
			);
		}
		return self::$instance;
	}

	/**
	 * @note Overriding \SESP\PropertyRegistry to remove registering properties
	 * associated with _EXIF.
	 *
	 * @since FIXME
	 *
	 * @return boolean
	 */
	public function registerPropertiesAndAliases() {
		$this->registerPropertiesFromList( array_keys( $this->definitions ) );
		return true;
	}

	
	/**
	 * @note Overriding \SESP\PropertyRegistry because for some reason
	 * \SESP\Cache\MessageCache doesn't display property labels correctly on
	 * "browse" page. This overriding method short-circuits around the 
	 * MessageCache object by directly accessing the MW message. The only line
	 * changed is:
	 *   return wfMessage( $msgkey )->inLanguage( $GLOBALS['wgContLang'] )->text();
	 * Which was:
	 *   return $this->messageCache->get( $msgkey );
	 * 
	 * @since FIXME
	 *
	 * @return boolean
	 */
	protected function getPropertyLabel( $id ) {
		$msgkey = $this->lookupWithIndexForId( 'msgkey', $id );
		if ( $msgkey ) {
			return wfMessage( $msgkey )->inLanguage( $GLOBALS['wgContLang'] )->text();
		}
		return false;
	}


}