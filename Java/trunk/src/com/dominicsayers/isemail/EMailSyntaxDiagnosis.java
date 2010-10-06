package com.dominicsayers.isemail;

/**
 * This enumeration contains all email syntax diagnosis values which can be
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
 * @version Java-Translation of is_email_statustext.php:r43 and
 *                              is_email.php:r44
 */

public enum EMailSyntaxDiagnosis {

	// No errors
	ISEMAIL_VALID(0, GeneralState.OK, "Address is valid"),

	// Warnings (valid address but unlikely in the real world)
	ISEMAIL_WARNING(64, GeneralState.WARNING, "Address is valid but unlikely in the real world"),
	ISEMAIL_TLD(65, GeneralState.WARNING, "Address is valid but at a Top Level Domain"),
	ISEMAIL_TLDNUMERIC(66, GeneralState.WARNING, "Address is valid but the Top Level Domain is numeric"),
	ISEMAIL_QUOTEDSTRING(67, GeneralState.WARNING, "Address is valid but contains a quoted string"),
	ISEMAIL_COMMENTS(68, GeneralState.WARNING, "Address is valid but contains comments"),
	ISEMAIL_FWS(69, GeneralState.WARNING, "Address is valid but contains Floating White Space"),
	ISEMAIL_ADDRESSLITERAL(70, GeneralState.WARNING, "Address is valid but at a literal address not a domain"),
	ISEMAIL_UNLIKELYINITIAL(71, GeneralState.WARNING, "Address is valid but has an unusual initial letter"),
	ISEMAIL_SINGLEGROUPELISION(72, GeneralState.WARNING, "Address is valid but contains a :: that only elides one zero group"),
	ISEMAIL_DOMAINNOTFOUND(73, GeneralState.WARNING, "Couldn't find an A record for this domain"),
	ISEMAIL_MXNOTFOUND(74, GeneralState.WARNING, "Couldn't find an MX record for this domain"),

	// Errors (invalid address)
	ISEMAIL_ERROR(128, GeneralState.ERROR, "Address is invalid"),
	ISEMAIL_TOOLONG(129, GeneralState.ERROR, "Address is too long"),
	ISEMAIL_NOAT(130, GeneralState.ERROR, "Address has no @ sign"),
	ISEMAIL_NOLOCALPART(131, GeneralState.ERROR, "Address has no local part"),
	ISEMAIL_NODOMAIN(132, GeneralState.ERROR, "Address has no domain part"),
	ISEMAIL_ZEROLENGTHELEMENT(133, GeneralState.ERROR, "Address has an illegal zero-length element (starts or ends with a dot or has two dots together)"),
	ISEMAIL_BADCOMMENT_START(134, GeneralState.ERROR, "Address contains illegal characters in a comment"),
	ISEMAIL_BADCOMMENT_END(135, GeneralState.ERROR, "Address contains illegal characters in a comment"),
	ISEMAIL_UNESCAPEDDELIM(136, GeneralState.ERROR, "Address contains an character that must be escaped but isn't"),
	ISEMAIL_EMPTYELEMENT(137, GeneralState.ERROR, "Address has an illegal zero-length element (starts or ends with a dot or has two dots together)"),
	ISEMAIL_UNESCAPEDSPECIAL(138, GeneralState.ERROR, "Address contains an character that must be escaped but isn't"),
	ISEMAIL_LOCALTOOLONG(139, GeneralState.ERROR, "The local part of the address is too long"),
	// ISEMAIL_IPV4BADPREFIX(140, GeneralState.ERROR, "The literal address contains an IPv4 address that is prefixed wrongly"),
	ISEMAIL_IPV6BADPREFIXMIXED(141, GeneralState.ERROR, "The literal address is wrongly prefixed"),
	ISEMAIL_IPV6BADPREFIX(142, GeneralState.ERROR, "The literal address is wrongly prefixed"),
	ISEMAIL_IPV6GROUPCOUNT(143, GeneralState.ERROR, "The IPv6 literal address contains the wrong number of groups"),
	ISEMAIL_IPV6DOUBLEDOUBLECOLON(144, GeneralState.ERROR, "The IPv6 literal address contains too many :: sequences"),
	// Revision 2.8: text amended to more accurately reflect the error condition
	ISEMAIL_IPV6BADCHAR(145, GeneralState.ERROR, "The IPv6 address contains an illegal group of characters"),
	ISEMAIL_IPV6TOOMANYGROUPS(146, GeneralState.ERROR, "The IPv6 address has too many groups"),
	ISEMAIL_DOMAINEMPTYELEMENT(147, GeneralState.ERROR, "The domain part contains an empty element"),
	ISEMAIL_DOMAINELEMENTTOOLONG(148, GeneralState.ERROR, "The domain part contains an element that is too long"),
	ISEMAIL_DOMAINBADCHAR(149, GeneralState.ERROR, "The domain part contains an illegal character"),
	ISEMAIL_DOMAINTOOLONG(150, GeneralState.ERROR, "The domain part is too long"),
	ISEMAIL_IPV6SINGLECOLONSTART(151, GeneralState.ERROR, "IPv6 address starts with a single colon"),
	ISEMAIL_IPV6SINGLECOLONEND(152, GeneralState.ERROR, "IPv6 address ends with a single colon");

	// Unexpected errors
	// revision 2.1: Redefined unexpected error constants so they don't clash with the ISEMAIL_WARNING bit
 	// revision 2.5: Undefined unused constants
	// ISEMAIL_BADPARAMETER(190, GeneralState.UNEXPECTED, "Unrecognised parameter"),
	// ISEMAIL_NOTDEFINED(191, GeneralState.UNEXPECTED, "Undefined error");
	
	// ----------------------------------------------------------------------------
	
	@Deprecated
	private int id; // The ID is obsolete in Java version and is only used in original PHP code
	private GeneralState state;
	private String diagnosisText;

	private EMailSyntaxDiagnosis(int id, GeneralState state, String diagnosisText) {
		this.id = id;
		this.state = state;
		this.diagnosisText = diagnosisText;
	}
	
	@Deprecated
	public int getId() {
		return id;
	}

	public GeneralState getState() {
		return state;
	}

	public String getDiagnosisText() {
		return diagnosisText;
	}
}
