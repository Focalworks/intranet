<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Google' => array(
            'client_id'     => '725897332478-3j26at5adbn26pt9gmg6cjnqlpvfo0r4.apps.googleusercontent.com',
            'client_secret' => 'ah6UWHdBlfVdibuFET7JyviN',
            'scope'         => array('userinfo_email', 'userinfo_profile'),
        ),		

	)

);