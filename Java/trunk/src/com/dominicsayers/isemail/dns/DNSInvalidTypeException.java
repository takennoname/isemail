package com.dominicsayers.isemail.dns;

/**
 * Appears when an invalid DNS type parameter was passed.
 * 
 * @author Daniel Marschall
 * @version 2010-10-08
 */
public class DNSInvalidTypeException extends DNSLookupException {

	private static final long serialVersionUID = -4538924241638595611L;

	public DNSInvalidTypeException() {
		super();
	}

	public DNSInvalidTypeException(String message) {
		super(message);
	}

	public DNSInvalidTypeException(Throwable cause) {
		super(cause);
	}

	public DNSInvalidTypeException(String message, Throwable cause) {
		super(message, cause);
	}

}
