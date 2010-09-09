package com.dominicsayers.isemail;

/**
 * This enumeration divides between the main parts of diagnosis.
 * 
 * @package isemail
 * @author Daniel Marschall [www.daniel-marschall.de]
 * @copyright 2010 by Daniel Marschall
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @see http://www.dominicsayers.com/isemail
 * @version 1.0 
 */

public enum GeneralState {
	
	OK,        // Original: 0
	WARNING,   // Original: 64..127
	ERROR,     // Original: 128..189
	UNEXPECTED // Original: 190..191

}
