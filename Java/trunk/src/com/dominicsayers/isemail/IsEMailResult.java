package com.dominicsayers.isemail;

import com.dominicsayers.isemail.smtpcode.SMTPResponse;

/**
 * This enumeration contains all email syntax result values which can be
 * returned by the email syntax validator.
 * 
 * @package isemail
 * @author Dominic Sayers <dominic_sayers@hotmail.com>;
 *         Translated from PHP into Java by Daniel Marschall
 *         [www.daniel-marschall.de]
 * @copyright 2008-2010 Dominic Sayers;
 *         Java-Translation 2010 by Daniel Marschall
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @see http://www.dominicsayers.com/isemail
 * @version 2010-10-18.
 *          Java-Translation of is_email_statustext.php:r68 and
 *                              is_email.php:r68
 */

public enum IsEMailResult {
	
	// No errors
	ISEMAIL_VALID(0, GeneralState.OK, "Address is valid", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),

	// Warnings (valid address but unlikely in the real world)
	ISEMAIL_WARNING(64, GeneralState.WARNING, "Address is valid but unlikely in the real world", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	ISEMAIL_TLD(65, GeneralState.WARNING, "Address is valid but at a Top Level Domain", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	ISEMAIL_TLDNUMERIC(66, GeneralState.WARNING, "Address is valid but the Top Level Domain is numeric", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	ISEMAIL_QUOTEDSTRING(67, GeneralState.WARNING, "Address is valid but contains a quoted string", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	ISEMAIL_COMMENTS(68, GeneralState.WARNING, "Address is valid but contains comments", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	ISEMAIL_FWS(69, GeneralState.WARNING, "Address is valid but contains Floating White Space", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	ISEMAIL_ADDRESSLITERAL(70, GeneralState.WARNING, "Address is valid but at a literal address not a domain", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	ISEMAIL_UNLIKELYINITIAL(71, GeneralState.WARNING, "Address is valid but has an unusual initial letter", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	ISEMAIL_SINGLEGROUPELISION(72, GeneralState.WARNING, "Address is valid but contains a :: that only elides one zero group", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	// Revision 2.10: text amended to reflect new DNS logic
	ISEMAIL_DOMAINNOTFOUND(73, GeneralState.WARNING, "Couldn't find an MX-record or an A-record for this domain", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),
	// Revision 2.10: text amended to reflect new DNS logic
	ISEMAIL_MXNOTFOUND(74, GeneralState.WARNING, "Couldn't find an MX record for this domain but an A-record does exist", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_250_215),

	// Errors (invalid address)
	ISEMAIL_ERROR(128, GeneralState.ERROR, "Address is invalid", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_510),
	ISEMAIL_TOOLONG(129, GeneralState.ERROR, "Address is too long", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_513),
	ISEMAIL_NOAT(130, GeneralState.ERROR, "Address has no @ sign", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_513),
	ISEMAIL_NOLOCALPART(131, GeneralState.ERROR, "Address has no local part", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_511),
	ISEMAIL_NODOMAIN(132, GeneralState.ERROR, "Address has no domain part", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_ZEROLENGTHELEMENT(133, GeneralState.ERROR, "Address has an illegal zero-length element (starts or ends with a dot or has two dots together)", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_511),
	ISEMAIL_BADCOMMENT_START(134, GeneralState.ERROR, "Address contains illegal characters in a comment", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_513),
	ISEMAIL_BADCOMMENT_END(135, GeneralState.ERROR, "Address contains illegal characters in a comment", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_513),
	ISEMAIL_UNESCAPEDDELIM(136, GeneralState.ERROR, "Address contains an character that must be escaped but isn't", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_511),
	ISEMAIL_EMPTYELEMENT(137, GeneralState.ERROR, "Address has an illegal zero-length element (starts or ends with a dot or has two dots together)", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_511),
	ISEMAIL_UNESCAPEDSPECIAL(138, GeneralState.ERROR, "Address contains an character that must be escaped but isn't", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_511),
	ISEMAIL_LOCALTOOLONG(139, GeneralState.ERROR, "The local part of the address is too long", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_511),
	// ISEMAIL_IPV4BADPREFIX(140, GeneralState.ERROR, "The literal address contains an IPv4 address that is prefixed wrongly", SMTPResponse.SMTP_553_512),
	ISEMAIL_IPV6BADPREFIXMIXED(141, GeneralState.ERROR, "The literal address is wrongly prefixed", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_IPV6BADPREFIX(142, GeneralState.ERROR, "The literal address is wrongly prefixed", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_IPV6GROUPCOUNT(143, GeneralState.ERROR, "The IPv6 literal address contains the wrong number of groups", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_IPV6DOUBLEDOUBLECOLON(144, GeneralState.ERROR, "The IPv6 literal address contains too many :: sequences", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	// Revision 2.8: text amended to more accurately reflect the error condition
	ISEMAIL_IPV6BADCHAR(145, GeneralState.ERROR, "The IPv6 address contains an illegal group of characters", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_IPV6TOOMANYGROUPS(146, GeneralState.ERROR, "The IPv6 address has too many groups", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_DOMAINEMPTYELEMENT(147, GeneralState.ERROR, "The domain part contains an empty element", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_DOMAINELEMENTTOOLONG(148, GeneralState.ERROR, "The domain part contains an element that is too long", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_DOMAINBADCHAR(149, GeneralState.ERROR, "The domain part contains an illegal character", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_DOMAINTOOLONG(150, GeneralState.ERROR, "The domain part is too long", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_IPV6SINGLECOLONSTART(151, GeneralState.ERROR, "IPv6 address starts with a single colon", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512),
	ISEMAIL_IPV6SINGLECOLONEND(152, GeneralState.ERROR, "IPv6 address ends with a single colon", SMTPResponse.ISEMAIL_STATUSTEXT_SMTP_553_512);

	// Unexpected errors
	// revision 2.1: Redefined unexpected error constants so they don't clash with the ISEMAIL_WARNING bit
 	// revision 2.5: Undefined unused constants
	// ISEMAIL_BADPARAMETER(190, GeneralState.UNEXPECTED, "Unrecognised parameter", SMTPResponse.SMTP_553_510),
	// ISEMAIL_NOTDEFINED(191, GeneralState.UNEXPECTED, "Undefined error", SMTPResponse.SMTP_553_510);
	
	// ----------------------------------------------------------------------------
	
	@Deprecated
	private int id;
	private GeneralState state;
	private String explanatory;
	private SMTPResponse smtpResponse;

	private IsEMailResult(int id, GeneralState state,
			String explanatory, SMTPResponse smtpResponse) {
		this.id = id;
		this.state = state;
		this.explanatory = explanatory;
		this.smtpResponse = smtpResponse;
	}

	/**
	 * Returns the ID of the result. Not necessary in Java implementation, but useful for interacting with PHP branch.
	 * @return The ID of the result.
	 */
	@Deprecated
	public int getId() {
		return id;
	}

	/**
	 * Returns the general state of the result. This general state
	 * tells you whether the address is OK or if warnings or errors appeared.
	 * 
	 * @return GeneralState that describes the result in general.
	 */
	public GeneralState getState() {
		return state;
	}

	/**
	 * Returns the text of the result.
	 * 
	 * @return The text of the result.
	 */
	public String getStatusTextExplanatory() {
		return explanatory;
	}
	
	/**
	 * Returns the SMTP response
	 * 
	 * @return The SMTP response object
	 */
	public SMTPResponse getSmtpResponse() {
		return smtpResponse;
	}

	/**
	 * Returns the constant name (for usage with PHP)
	 * 
	 * @return PHP constant name.
	 */
	public String getConstantName() {
		return super.toString();
	}
	
	/**
	 * Outputs a description of the result.
	 * 
	 * @return Description with constant name as well as explanatory.
	 */
	public String toString() {
		return "["+getConstantName()+"] " + getStatusTextExplanatory();		
	}
}
