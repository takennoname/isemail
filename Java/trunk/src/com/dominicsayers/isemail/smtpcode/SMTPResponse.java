package com.dominicsayers.isemail.smtpcode;


/**
 * Contains SMTP status codes used in IsEMail. This list is not complete. Only
 * used entries are listed here as constants.
 * 
 * @author Daniel Marschall
 * @version 2010-10-11
 * 
 */
public enum SMTPResponse {
	
	// TODO FUTURE: Make a full list of ALL possible codes
	// (just to have a complete class)?
	
	ISEMAIL_STATUSTEXT_SMTP_250_215(250, "2.1.5", "ok"),
	ISEMAIL_STATUSTEXT_SMTP_553_510(533, "5.1.0", "Other address status"),
	ISEMAIL_STATUSTEXT_SMTP_553_511(533, "5.1.1", "Bad destination mailbox address"),
	ISEMAIL_STATUSTEXT_SMTP_553_512(533, "5.1.2", "Bad destination system address"),
	ISEMAIL_STATUSTEXT_SMTP_553_513(533, "5.1.3", "Bad destination mailbox address syntax");
	
	private int smtpCode;
	private ESMTPCode esmtpCode;
	private String messagetext;

	private SMTPResponse(int smtpCode, String esmtpCode, String messagetext) {
		this.smtpCode = smtpCode;
		try {
			this.esmtpCode = new ESMTPCode(esmtpCode);
		} catch (ESMTPCodeException e) {
			System.out.println("Internal error in construction of SMTPResponse!");
		}
		this.messagetext = messagetext;
	}
	
	/**
	 * Returns the old style SMTP status code (for backwards compatibility
	 * with old mail servers).
	 *  
	 * @return The old style SMTP status code.
	 */
	public int getSmtpCode() {
		return smtpCode;
	}
	
	/**
	 * Returns the new enhanced SMTP (ESMTP) status code.
	 * 
	 * @return The new enhanced SMTP (ESMTP) status code.
	 */
	public ESMTPCode getEsmtpCode() {
		return esmtpCode;
	}

	/**
	 * Returns a message text to that response (without the code prefix!)
	 * 
	 * @return Response message text.
	 */
	public String getMessageText() {
		return messagetext;
	}
	
	/**
	 * Returns the SMTP response line with old style SMTP code, ESMTP code
	 * and message text.
	 * 
	 * @return SMTP response line.
	 */
	public String getResponseLine() {
		return smtpCode + " " + esmtpCode + " " + messagetext;		
	}

	/**
	 * Returns the SMTP response line.
	 * 
	 * @return SMTP response line.
	 */
	@Override
	public String toString() {
		return getResponseLine();
	}

}
