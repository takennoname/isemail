package com.dominicsayers.isemail.dns;

/**
 * Appears when the creation of the initial context failed.
 * 
 * @author Daniel Marschall
 * @version 2010-10-08
 */
public class DNSInitialContextException extends DNSLookupException {

	private static final long serialVersionUID = -490130028402981235L;

	public DNSInitialContextException() {
		super();
	}

	public DNSInitialContextException(String message) {
		super(message);
	}

	public DNSInitialContextException(Throwable cause) {
		super(cause);
	}

	public DNSInitialContextException(String message, Throwable cause) {
		super(message, cause);
	}

}
