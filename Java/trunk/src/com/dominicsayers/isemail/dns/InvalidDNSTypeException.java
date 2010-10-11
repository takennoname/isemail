package com.dominicsayers.isemail.dns;

/**
 * Appears when an invalid DNS type parameter was passed.
 * 
 * @author Daniel Marschall
 * @version 2010-10-08
 */
public class InvalidDNSTypeException extends DNSLookupException {

	private static final long serialVersionUID = -4538924241638595611L;

	public InvalidDNSTypeException() {
		super();
	}

	public InvalidDNSTypeException(String message) {
		super(message);
	}

	public InvalidDNSTypeException(Throwable cause) {
		super(cause);
	}

	public InvalidDNSTypeException(String message, Throwable cause) {
		super(message, cause);
	}

}
