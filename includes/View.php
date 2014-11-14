<?php

namespace MeetingMinutes;

class View {

	/**
	 * $var string $viewPath: Path to location of template view files
	 */
	protected $viewPath;
	
	/**
	 * @var object $templateEngine: holds template engine object. Currently
	 * using Mustache, but may switch soon
	 */
	protected $templateEngine;

	/**
	 * @var string $templateText: Holds text taken from template file
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
	
		$this->viewPath = __DIR__ . '/../views';
		
		$this->templateText = file_get_contents( $this->viewPath . '/' . $viewName );
		$this->templateEngine = new \Mustache_Engine;

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
		
		return $this->templateEngine->render( $this->templateText, $model );
		
	}

}