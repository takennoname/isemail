package com.dominicsayers.isemail;

/**
 * Contains SMTP status codes used in IsEMail. This list is not complete. Only
 * used entries are listed here as constants.
 * 
 * @author Daniel Marschall
 * @version 2010-10-08
 * 
 */
public class SMTPCodes {

	public static final String SMTP_553_510 = "553 5.1.0 Other address status";
	public static final String SMTP_553_511 = "553 5.1.1 Bad destination mailbox address";
	public static final String SMTP_553_512 = "553 5.1.2 Bad destination system address";
	public static final String SMTP_553_513 = "553 5.1.3 Bad destination mailbox address syntax";
	public static final String SMTP_250 = "250 2.1.5 ok";

	private SMTPCodes() {
	}

}
