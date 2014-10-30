<?php

namespace MeetingMinutes;

/**
 * Container for the settings contained by this extension.
 * Structure of this file was taken from the SubPageList extension.
 * Thanks to Jeroen De Dauw
 *
 * @licence GNU GPL v2+
 * @author James Montalvo
 */
class Settings {

	/**
	 * Constructs a new instance of the settings object from global state.
	 *
	 * @param array $globalVariables
	 *
	 * @return Settings
	 */
	public static function newFromGlobals( array $globalVariables ) {
		return new self( array(
			// currently no settings
			// self::AUTO_REFRESH => $globalVariables['egSPLAutorefresh'],
		) );
	}

	// const AUTO_REFRESH = 'autorefresh'; // handle settings like this?

	/**
	 * @var array
	 */
	private $settings;

	/**
	 * Constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( array $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Returns the setting with the provided name.
	 * The specified setting needs to exist.
	 *
	 * @param string $settingName
	 *
	 * @return mixed
	 */
	public function get( $settingName ) {
		return $this->settings[$settingName];
	}

}