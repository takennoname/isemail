package com.dominicsayers.isemail;

/**
 * This enumeration divides between the main parts of diagnosis.
 * 
 * @package isemail
 * @author Daniel Marschall [www.daniel-marschall.de]
 * @copyright 2010 by Daniel Marschall
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @see http://www.dominicsayers.com/isemail
 * @version 2010-10-06
 */

public enum GeneralState {

	OK(true), // Original: 0
	WARNING(true), // Original: 64..74
	ERROR(false) // Original: 128..152
	// ,UNEXPECTED(false) // Original: 190..191
	;

	boolean isValid;

	public boolean isValid() {
		return this.isValid;
	}

	private GeneralState(boolean isValid) {
		this.isValid = isValid;
	}

}
