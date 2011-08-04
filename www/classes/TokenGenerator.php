<?php

// This class is responsible for generating tokens
// for the RAS system. It was taken, unmodified, from 
// the following URL:
// http://www.itnewb.com/v/Generating-Session-IDs-and-Random-Passwords-with-PHP
class TokenGenerator {
	function getToken( $len = 8, $special = true ) {
     
        # Seed random number generator
        # Only needed for PHP versions prior to 4.2
        mt_srand( (double)microtime()*1000000 );
     
        # Array of digits, lower and upper characters; empty passwd string
        $passwd = '';
        $chars = array(
            'digits' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            'lower' => array(
                'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
                'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
            ),
            'upper' => array(
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
            )
        );
     
        # Add special chars to array, if permitted; adjust as desired
        if ( $special ) $chars['special'] = array(
            '!', '@', '#', '$', '%', '^', '&', '*', '_', '+'
        );
     
        # Array indices (ei- digits, lower, upper)
        $charTypes = array_keys($chars);
        # Array indice friendly number of char types
        $numTypes = count($charTypes) - 1;
     
        # Create random password
        for ( $i=0; $i<$len; $i++ ) {
     
            # Random char type
            $charType = $charTypes[ mt_rand(0, $numTypes) ];
            # Append random char to $passwd
            $passwd .= $chars[$charType][
                mt_rand(0, count( $chars[$charType] ) - 1 )
            ];
     
        } return $passwd;
    }
}