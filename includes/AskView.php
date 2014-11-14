<?php

namespace MeetingMinutes;

class AskView extends View {
	
	/**
	 * @var object $templateEngine: holds template engine object. Currently
	 * using Mustache, but may switch soon
	 */
	protected $templateEngine;

	/**
	 * @var object $templateText: Holds text taken from template file
	 */
	protected $templateText;

	
	/**
	 * Description (FIXME:Docs)
	 *
	 * @since 1.1.0 (FIXME:Docs)
	 *
	 * @param array|string $options Description (FIXME:Docs)
	 * @return Foo|null: Description (FIXME:Docs)
	 *
	 * Some example: (FIXME:Docs)
	 * @code
	 * ...
	 * @endcode
	 */
	public function __construct ( $viewName ) {
	
		parent::__construct( 'ask/' . $viewName );
	
	}

	/**
	 * Description (FIXME:Docs)
	 *
	 * @since 0.1.0 (FIXME:Docs)
	 *
	 * @param array|string $options Description (FIXME:Docs)
	 * @return Foo|null: Description (FIXME:Docs)
	 *
	 * Some example: (FIXME:Docs)
	 * @code
	 * ...
	 * @endcode
	 */
	public function render ( $model ) {
		
		$renderedTemplate = $this->templateEngine->render( $this->templateText, $model );
		
		// filter through MediaWiki message system to parse (FIXME: is there a
		// better way to do this?)
		$msg = new \RawMessage( $renderedTemplate );
		return $msg->text();
		
	}

}