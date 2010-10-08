package com.dominicsayers.isemail.dns;

/**
 * Appears on a fatal error.
 * 
 * @author Daniel Marschall
 * @version 2010-10-08
 */
public class DNSLookupException extends Exception {

	private static final long serialVersionUID = -5080752496667780199L;

	public DNSLookupException() {
		super();
	}

	public DNSLookupException(String message) {
		super(message);
	}

	public DNSLookupException(Throwable cause) {
		super(cause);
	}

	public DNSLookupException(String message, Throwable cause) {
		super(message, cause);
	}
	
}
