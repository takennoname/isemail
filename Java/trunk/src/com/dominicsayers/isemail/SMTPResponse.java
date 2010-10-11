package com.dominicsayers.isemail;

/**
 * Contains SMTP status codes used in IsEMail. This list is not complete. Only
 * used entries are listed here as constants.
 * 
 * @author Daniel Marschall
 * @version 2010-10-11
 * 
 */
public enum SMTPResponse {
	
	// Info: http://www.unixhub.com/docs/email/SMTPcodes.html
	
	// TODO FUTURE: Make a full list of ALL possible codes
	// (just to have a complete class)?
	
	ISEMAIL_STATUSTEXT_SMTP_250_215(250, "2.1.5", "ok"),
	ISEMAIL_STATUSTEXT_SMTP_553_510(533, "5.1.0", "Other address status"),
	ISEMAIL_STATUSTEXT_SMTP_553_511(533, "5.1.1", "Bad destination mailbox address"),
	ISEMAIL_STATUSTEXT_SMTP_553_512(533, "5.1.2", "Bad destination system address"),
	ISEMAIL_STATUSTEXT_SMTP_553_513(533, "5.1.3", "Bad destination mailbox address syntax");
	
	private int esmtpCode;
	private String oldCode;
	private String text;

	private SMTPResponse(int esmtpCode, String oldCode, String text) {
		this.esmtpCode = esmtpCode;
		this.oldCode = oldCode;
		this.text = text;
	}
	
	/**
	 * Returns the new enhanced SMTP (ESMTP) status code.
	 * 
	 * @return The new enhanced SMTP (ESMTP) status code.
	 */
	public int getEsmtpCode() {
		return esmtpCode;
	}

	/**
	 * Returns the old style SMTP status code (for backwards compatibility
	 * with old mail servers).
	 *  
	 * @return The old style SMTP status code.
	 */
	public String getOldCode() {
		return oldCode;
	}

	/**
	 * Returns a message text to that response (without the code prefix!)
	 * 
	 * @return Response message text.
	 */
	public String getText() {
		return text;
	}

	/**
	 * Returns the SMTP response line with ESMTP code, old style SMTP code
	 * and message text.
	 * 
	 * @return SMTP response line.
	 */
	@Override
	public String toString() {
		return esmtpCode + " " + oldCode + " " + text;
	}
	

}
