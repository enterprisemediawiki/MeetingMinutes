<?php

namespace MeetingMinutes;

use Parser;
use ParserHooks\HookDefinition;
use ParserHooks\HookHandler;
use ParserHooks\HookRegistrant;
// use SubPageList\UI\SubPageListRenderer;
// use SubPageList\UI\WikitextSubPageListRenderer;

/**
 * Top level factory for the MeetingMinutes extension.
 * Structure of this file was taken from the SubPageList extension.
 * Thanks to Jeroen De Dauw
 *
 * @licence GNU GPL v2+
 * @author James Montalvo
 */
class Extension {

	/**
	 * @var Settings
	 */
	private $settings;

	public function __construct( Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * @return Settings
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * @return DBConnectionProvider
	 */
	public function getSlaveConnectionProvider() {
		return new LazyDBConnectionProvider( DB_SLAVE );
	}

	/**
	 * @return CacheInvalidator
	 */
	public function getCacheInvalidator() {
		return new SimpleCacheInvalidator( $this->getSubPageFinder() );
	}

	/**
	 * @return SimpleSubPageFinder
	 */
	public function getSubPageFinder() {
		return new SimpleSubPageFinder( $this->getSlaveConnectionProvider() );
	}

	/**
	 * @return SubPageCounter
	 */
	public function getSubPageCounter() {
		return new SimpleSubPageFinder( $this->getSlaveConnectionProvider() );
	}

	/**
	 * @return TitleFactory
	 */
	public function getTitleFactory() {
		return new TitleFactory();
	}

	/**
	 * @return HookHandler
	 */
	public function getCountHookHandler() {
		return new SubPageCount( $this->getSubPageCounter(), $this->getTitleFactory() );
	}

	/**
	 * @return HookHandler
	 */
	public function getListHookHandler() {
		return new SubPageList(
			$this->getSubPageFinder(),
			$this->getPageHierarchyCreator(),
			$this->newSubPageListRenderer(),
			$this->getTitleFactory()
		);
	}

	/**
	 * @return PageHierarchyCreator
	 */
	public function getPageHierarchyCreator() {
		return new PageHierarchyCreator( $this->getTitleFactory() );
	}

	/**
	 * @return SubPageListRenderer
	 */
	public function newSubPageListRenderer() {
		return new WikitextSubPageListRenderer();
	}

	/**
	 * @return HookDefinition
	 */
	public function getCountHookDefinition() {
		return new HookDefinition(
			'subpagecount',
			array(
				'page' => array(
					'default' => '',
					'aliases' => 'parent',
					'message' => 'spl-subpages-par-page',
				),
			),
			'page'
		);
	}

	/**
	 * @return HookDefinition
	 */
	public function getListHookDefinition() {
		$params = array();

		$params['page'] = array(
			'aliases' => 'parent',
			'default' => '',
		);

		$params['showpage'] = array(
			'type' => 'boolean',
			'aliases' => 'showparent',
			'default' => false,
		);

		$params['sort'] = array(
			'aliases' => 'order',
			'values' => array( 'asc', 'desc' ),
			'tolower' => true,
			'default' => 'asc',
		);

		$params['intro'] = array(
			'default' => '',
		);

		$params['outro'] = array(
			'default' => '',
		);

		$params['links'] = array(
			'type' => 'boolean',
			'aliases' => 'link',
			'default' => true,
		);

		$params['default'] = array(
			'default' => '',
		);

		$params['limit'] = array(
			'type' => 'integer',
			'default' => 200,
			'range' => array( 1, 500 ),
		);

		$params['element'] = array(
			'default' => 'div',
			'aliases' => array( 'div', 'p', 'span' ),
		);

		$params['class'] = array(
			'default' => 'subpagelist',
		);

		$params['format'] = array(
			'aliases' => 'liststyle',
			'values' => array(
				'ul', 'unordered',
				'ol', 'ordered',
//				'list', 'bar' // TODO: re-implement support for these two
			),
			'tolower' => true,
			'default' => 'ul',
		);

		$params['pathstyle'] = array(
			'aliases' => 'showpath',
			'values' => array(
				'none', 'no',
				'subpagename', 'children', 'notparent',
				'pagename',
				'full',
				'fullpagename'
			),
			'tolower' => true,
			'default' => 'subpagename',
		);

		$params['kidsonly'] = array(
			'type' => 'boolean',
			'default' => false,
		);

		$params['template'] = array(
			'default' => '',
		);

		// TODO: re-implement support
//		$params['separator'] = array(
//			'aliases' => 'sep',
//			'default' => '&#160;· ',
//		);

		// Give grep a chance to find the usages:
		// spl-subpages-par-sort, spl-subpages-par-sortby, spl-subpages-par-format, spl-subpages-par-page,
		// spl-subpages-par-showpage, spl-subpages-par-pathstyle, spl-subpages-par-kidsonly, spl-subpages-par-limit,
		// spl-subpages-par-element, spl-subpages-par-class, spl-subpages-par-intro, spl-subpages-par-outro,
		// spl-subpages-par-default, spl-subpages-par-separator, spl-subpages-par-template, spl-subpages-par-links
		foreach ( $params as $name => &$param ) {
			$param['message'] = 'spl-subpages-par-' . $name;
		}

		return new HookDefinition(
			array( 'subpagelist', 'splist', 'subpages' ),
			$params,
			array( 'page', 'format', 'pathstyle', 'sort' )
		);
	}
	
	
	/**
	 * @return HookDefinition
	 */
	public function getMeetingHookDefinition() {
		$params = array();

		$params['title'] = array(
			'default' => 'Meeting Title',
		);

		$params['day'] = array(
			'default' => 'Monday',
			'values' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
		);

		$params['time'] = array(
			'default' => '08:00',
		);

		$params['building'] = array(
			'default' => '',
		);

		$params['room'] = array(
			'default' => '',
		);

		$params['phonenumber'] = array(
			'default' => '',
		);

		$params['phonepassword'] = array(
			'default' => '',
		);

		$params['attendees'] = array(
			'default' => '',
		);

		$params['overview'] = array(
			'default' => '',
		);

		// Give grep a chance to find the usages:
		//  ext-mm-meeting-parameter-title ext-mm-meeting-parameter-day
		//  ext-mm-meeting-parameter-time ext-mm-meeting-parameter-building
		//  ext-mm-meeting-parameter-room ext-mm-meeting-parameter-phonenumber
		//  ext-mm-meeting-parameter-phonepassword ext-mm-meeting-parameter-attendees
		//  ext-mm-meeting-parameter-overview
		foreach ( $params as $name => &$param ) {
			$param['message'] = 'ext-mm-meeting-parameter-' . $name;
		}

		return new HookDefinition(
			array( 'meeting' ),
			$params,
			array( 'title' )
		);
	}
	
	/**
	 * @param Parser $parser
	 *
	 * @return HookRegistrant
	 */
	public function getHookRegistrant( Parser &$parser ) {
		return new HookRegistrant( $parser );
	}

}