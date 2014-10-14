<?php

return array(
  
    /**
     * This is the method which will be used for sending mail.
     * Currently there can be these following methods:
     * smpt - like gmail smtp and port with SSL
     * sendmail
     */
    'method' => 'smtp',
    'smtp' => array(
        'username' => '',
        'password' => '',
        'server' => '',
        'port' => '',
        'encryption' => ''
    ),
    
);