package com.dominicsayers.isemail.smtpcode;

/**
 * 
 * @author Daniel Marschall
 * @see http://tools.ietf.org/html/rfc3463
 * 
 */
public enum ESMTPClass {

	ESMTP_CLASS_SUCCESS(2),
	ESMTP_CLASS_PERSISTENT_TRANSIENT_FAILURE(4),
	ESMTP_CLASS_PERMANENT_FAILURE(5);

	private int code;

	private ESMTPClass(int code) {
		this.code = code;
	}

	public int getCode() {
		return code;
	}

	public static boolean isValid(int code) {
		for (ESMTPClass x : values()) {
			if (code == x.code)
				return true;
		}
		return false;
	}

}
