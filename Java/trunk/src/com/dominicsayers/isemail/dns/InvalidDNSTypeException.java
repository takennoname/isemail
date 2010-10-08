package com.dominicsayers.isemail.dns;

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
