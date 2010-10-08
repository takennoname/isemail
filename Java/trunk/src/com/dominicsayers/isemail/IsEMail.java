package com.dominicsayers.isemail;

import com.dominicsayers.isemail.dns.DNSLookup;
import com.dominicsayers.isemail.dns.DNSLookupException;

/**
 * To validate an email address according to RFCs 5321, 5322 and others
 * 
 * Copyright © 2008-2010, Dominic Sayers <br>
 * Test schema documentation Copyright © 2010, Daniel Marschall <br>
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 
 * - Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. - Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials provided
 * with the distribution. - Neither the name of Dominic Sayers nor the names of
 * its contributors may be used to endorse or promote products derived from this
 * software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * 
 * @package com.dominicsayers.isemail
 * @author Dominic Sayers <dominic@sayers.cc><br>
 *         Translated from PHP into Java by Daniel Marschall
 *         [www.daniel-marschall.de]
 * @copyright 2008-2010 Dominic Sayers; Java-Translation 2010 by Daniel
 *            Marschall
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link http://www.dominicsayers.com/isemail
 * @version 2010-10-08. Java-Translation of isemail.php:r62.
 */

public class IsEMail {

	/**
	 * Checks the syntax of an email address without DNS check.
	 * 
	 * @param email
	 *            The email address to be checked.
	 * @return True if the email address is valid.
	 * @throws DNSLookupException
	 *             Is thrown if an internal error in the DNS lookup appeared.
	 */
	public static boolean is_email(String email) throws DNSLookupException {
		return (is_email_verbose(email, false).getState().isValid);
	}

	/**
	 * Checks the syntax of an email address.
	 * 
	 * @param email
	 *            The email address to be checked.
	 * @param checkDNS
	 *            Whether a DNS check should be performed or not.
	 * @return True if the email address is valid.
	 * @throws DNSLookupException
	 *             Is thrown if an internal error in the DNS lookup appeared.
	 */
	public static boolean is_email(String email, boolean checkDNS)
			throws DNSLookupException {
		return (is_email_verbose(email, checkDNS).getState() == GeneralState.OK);
	}

	/**
	 * Checks the syntax of an email address with verbose information and
	 * without DNS check.
	 * 
	 * @param email
	 *            The email address to be checked.
	 * @return A verbose information of the email syntax.
	 * @throws DNSLookupException
	 *             Is thrown if an internal error in the DNS lookup appeared.
	 */
	public static IsEMailResult is_email_verbose(String email)
			throws DNSLookupException {
		return is_email_verbose(email, false);
	}

	/**
	 * Check that an email address conforms to RFCs 5321, 5322 and others. With
	 * verbose information.
	 * 
	 * Check that an email address conforms to RFCs 5321, 5322 and others
	 * 
	 * @param email
	 *            The email address to check
	 * @param checkDNS
	 *            If true then a DNS check for A and MX records will be made
	 * @return Result-Object of the email analysis.
	 * @throws DNSLookupException
	 *             Is thrown if an internal error in the DNS lookup appeared.
	 */
	public static IsEMailResult is_email_verbose(String email, boolean checkDNS)
			throws DNSLookupException {

		// Translation note: $warn=true and $diagnosis=true , so this method
		// will always output exact diagnosis code inclusive warnings. The other
		// overloaded methods can ignore warnings then.

		if (email == null)
			email = "";

		// Check that $email is a valid address. Read the following RFCs to
		// understand the constraints:
		// (http://tools.ietf.org/html/rfc5321)
		// (http://tools.ietf.org/html/rfc5322)
		// (http://tools.ietf.org/html/rfc4291#section-2.2)
		// (http://tools.ietf.org/html/rfc1123#section-2.1)
		// (http://tools.ietf.org/html/rfc3696) (guidance only)

		IsEMailResult return_status = IsEMailResult.ISEMAIL_VALID;
		// version 2.0: Enhance $diagnose parameter to $errorlevel

		// the upper limit on address lengths should normally be considered to
		// be 254
		// (http://www.rfc-editor.org/errata_search.php?rfc=3696)
		// NB My erratum has now been verified by the IETF so the correct answer
		// is 254
		//
		// The maximum total length of a reverse-path or forward-path is 256
		// characters (including the punctuation and element separators)
		// (http://tools.ietf.org/html/rfc5321#section-4.5.3.1.3)
		// NB There is a mandatory 2-character wrapper round the actual address
		int emailLength = email.length();
		// revision 1.17: Max length reduced to 254 (see above)
		if (emailLength > 254) {
			return IsEMailResult.ISEMAIL_TOOLONG; // Too long
		}

		// Contemporary email addresses consist of a "local part" separated from
		// a "domain part" (a fully-qualified domain name) by an at-sign ("@").
		// (http://tools.ietf.org/html/rfc3696#section-3)
		int atIndex = email.lastIndexOf('@');

		if (atIndex == -1) {
			return IsEMailResult.ISEMAIL_NOAT; // No at-sign
		}
		if (atIndex == 0) {
			return IsEMailResult.ISEMAIL_NOLOCALPART; // No local part
		}
		if (atIndex == emailLength - 1) {
			// No domain part
			return IsEMailResult.ISEMAIL_NODOMAIN;
			// revision 1.14: Length test bug suggested by Andrew Campbell of
			// Gloucester, MA
		}

		// Sanitize comments
		// - remove nested comments, quotes and dots in comments
		// - remove parentheses and dots from quoted strings
		int braceDepth = 0;
		boolean inQuote = false;
		boolean escapeThisChar = false;

		for (int i = 0; i < emailLength; ++i) {
			char charX = email.charAt(i);
			boolean replaceChar = false;

			if (charX == '\\') {
				escapeThisChar = !escapeThisChar; // Escape the next character?
			} else {
				switch (charX) {
				case '(':
					if (escapeThisChar) {
						replaceChar = true;
					} else {
						if (inQuote) {
							replaceChar = true;
						} else {
							if (braceDepth++ > 0) {
								replaceChar = true; // Increment brace depth
							}
						}
					}

					break;
				case ')':
					if (escapeThisChar) {
						replaceChar = true;
					} else {
						if (inQuote) {
							replaceChar = true;
						} else {
							if (--braceDepth > 0)
								replaceChar = true; // Decrement brace depth
							if (braceDepth < 0) {
								braceDepth = 0;
							}
						}
					}

					break;
				case '"':
					if (escapeThisChar) {
						replaceChar = true;
					} else {
						if (braceDepth == 0) {
							// Are we inside a quoted string?
							inQuote = !inQuote;
						} else {
							replaceChar = true;
						}
					}

					break;
				case '.': // Dots don't help us either
					if (escapeThisChar) {
						replaceChar = true;
					} else {
						if (braceDepth > 0)
							replaceChar = true;
					}

					break;
				default:
				}

				escapeThisChar = false;
				if (replaceChar) {
					// Replace the offending character with something harmless
					// revision 1.12: Line above replaced because PHPLint
					// doesn't like that syntax
					email = replaceCharAt(email, i, 'x');
				}

			}
		}

		String localPart = PHPFunctions.substr(email, 0, atIndex);
		String domain = PHPFunctions.substr(email, atIndex + 1);
		// Folding white space
		final String FWS = "(?:(?:(?:[ \\t]*(?:\\r\\n))?[ \\t]+)|(?:[ \\t]+(?:(?:\\r\\n)[ \\t]+)*))";

		// Let's check the local part for RFC compliance...
		//
		// local-part = dot-atom / quoted-string / obs-local-part
		// obs-local-part = word *("." word)
		// (http://tools.ietf.org/html/rfc5322#section-3.4.1)
		//
		// Problem: need to distinguish between "first.last" and "first"."last"
		// (i.e. one element or two). And I suck at regular expressions.

		String[] dotArray = PHPFunctions.preg_split(
				"(?m)\\.(?=(?:[^\\\"]*\\\"[^\\\"]*\\\")*(?![^\\\"]*\\\"))",
				localPart);
		int partLength = 0;

		for (String element : dotArray) {
			// Remove any leading or trailing FWS
			String new_element = PHPFunctions.preg_replace("^" + FWS + "|"
					+ FWS + "$", "", element);

			if (!element.equals(new_element)) {
				// FWS is unlikely in the real world
				return_status = IsEMailResult.ISEMAIL_FWS;
			}
			element = new_element; // version 2.3: Warning condition added

			int elementLength = element.length();

			if (elementLength == 0) {
				// Can't have empty element (consecutive dots or
				// dots at the start or end)
				return IsEMailResult.ISEMAIL_ZEROLENGTHELEMENT;
			}
			// revision 1.15: Speed up the test and get rid of
			// "uninitialized string offset" notices from PHP

			// We need to remove any valid comments (i.e. those at the start or
			// end of the element)
			if (element.charAt(0) == '(') {
				// Comments are unlikely in the real world
				return_status = IsEMailResult.ISEMAIL_COMMENTS;
				// version 2.0: Warning condition added
				int indexBrace = element.indexOf(')');
				if (indexBrace != -1) {
					if (PHPFunctions.preg_match("(?<!\\\\)[\\(\\)]",
							PHPFunctions.substr(element, 1, indexBrace - 1)) > 0) {
						// Illegal characters in comment
						return IsEMailResult.ISEMAIL_BADCOMMENT_START;
					}
					element = PHPFunctions.substr(element, indexBrace + 1,
							elementLength - indexBrace - 1);
					elementLength = element.length();
				}
			}

			if (element.charAt(elementLength - 1) == ')') {
				// Comments are unlikely in the real world
				return_status = IsEMailResult.ISEMAIL_COMMENTS;
				// version 2.0: Warning condition added
				int indexBrace = element.lastIndexOf('(');
				if (indexBrace != -1) {
					if (PHPFunctions.preg_match("(?<!\\\\)(?:[\\(\\)])",
							PHPFunctions.substr(element, indexBrace + 1,
									elementLength - indexBrace - 2)) > 0) {
						// Illegal characters in comment
						return IsEMailResult.ISEMAIL_BADCOMMENT_END;
					}
					element = PHPFunctions.substr(element, 0, indexBrace);
					elementLength = element.length();
				}
			}

			// Remove any remaining leading or trailing FWS around the element
			// (having removed any comments)
			new_element = PHPFunctions.preg_replace(
					"^" + FWS + "|" + FWS + "$", "", element);
			// FWS is unlikely in the real world
			if (!element.equals(new_element))
				return_status = IsEMailResult.ISEMAIL_FWS;
			element = new_element;
			// version 2.0: Warning condition added

			// What's left counts towards the maximum length for this part
			if (partLength > 0)
				partLength++; // for the dot
			partLength += element.length();

			// Each dot-delimited component can be an atom or a quoted string
			// (because of the obs-local-part provision)

			if (PHPFunctions.preg_match("(?s)^\"(?:.)*\"$", element) > 0) {
				// Quoted-string tests:
				// Quoted string is unlikely in the real world
				return_status = IsEMailResult.ISEMAIL_QUOTEDSTRING;
				// version 2.0: Warning condition added
				// Remove any FWS
				// A warning condition, but we've already raised
				// ISEMAIL_QUOTEDSTRING
				element = PHPFunctions.preg_replace("(?<!\\\\)" + FWS, "",
						element);
				// My regular expression skills aren't up to distinguishing
				// between \" \\" \\\" \\\\" etc.
				// So remove all \\ from the string first...
				element = PHPFunctions.preg_replace("\\\\\\\\", " ", element);
				if (PHPFunctions
						.preg_match(
								"(?<!\\\\|^)[\"\\r\\n\\x00](?!$)|\\\\\"$|\"\"",
								element) > 0) {
					// ", CR, LF and NUL must be escaped
					// version 2.0: allow ""@example.com because it's
					// technically valid
					return IsEMailResult.ISEMAIL_UNESCAPEDDELIM;
				}
			} else {
				// Unquoted string tests:
				//
				// Period (".") may...appear, but may not be used to start or
				// end the
				// local part, nor may two or more consecutive periods appear.
				// (http://tools.ietf.org/html/rfc3696#section-3)
				//
				// A zero-length element implies a period at the beginning or
				// end of the
				// local part, or two periods together. Either way it's not
				// allowed.
				if (element.isEmpty()) {
					// Dots in wrong place
					return IsEMailResult.ISEMAIL_EMPTYELEMENT;
				}

				// Any ASCII graphic (printing) character other than the
				// at-sign ("@"), backslash, double quote, comma, or square
				// brackets may
				// appear without quoting. If any of that list of excluded
				// characters
				// are to appear, they must be quoted
				// (http://tools.ietf.org/html/rfc3696#section-3)
				//
				// Any excluded characters? i.e. 0x00-0x20, (, ), <, >, [, ], :,
				// ;, @, \, comma, period, "
				if (PHPFunctions.preg_match(
						"[\\x00-\\x20\\(\\)<>\\[\\]:;@\\\\,\\.\"]", element) > 0) {
					// These characters must be in a quoted string
					return IsEMailResult.ISEMAIL_UNESCAPEDSPECIAL;
				}
				if (PHPFunctions.preg_match("^\\w+", element) == 0) {
					// First character is an odd one
					return_status = IsEMailResult.ISEMAIL_UNLIKELYINITIAL;
				}
			}
		}

		if (partLength > 64) {
			// Local part must be 64 characters or less
			return IsEMailResult.ISEMAIL_LOCALTOOLONG;
		}

		// Now let's check the domain part...

		// The domain name can also be replaced by an IP address in square
		// brackets
		// (http://tools.ietf.org/html/rfc3696#section-3)
		// (http://tools.ietf.org/html/rfc5321#section-4.1.3)
		// (http://tools.ietf.org/html/rfc4291#section-2.2)

		if (PHPFunctions.preg_match("^\\[(.)+]$", domain) == 1) {
			// It's an address-literal
			// Quoted string is unlikely in the real world
			return_status = IsEMailResult.ISEMAIL_ADDRESSLITERAL;
			// version 2.0: Warning condition added
			String addressLiteral = PHPFunctions.substr(domain, 1, domain
					.length() - 2);

			String IPv6;
			int groupMax = 8;
			// revision 2.1: new IPv6 testing strategy

			final String colon = ":"; // Revision 2.7: Daniel Marschall's new
			// IPv6 testing strategy
			final String double_colon = "::";

			// Extract IPv4 part from the end of the address-literal (if there
			// is one)
			String[] matchesIP = PHPFunctions
					.preg_match_to_array(
							"\\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$",
							addressLiteral);
			if (matchesIP.length > 0) {
				int index = addressLiteral.lastIndexOf(matchesIP[0]);

				if (index == 0) {
					// Nothing there except a valid IPv4 address, so...
					return return_status;
					// version 2.0: return warning if one is set
				} else {
					// - // Assume it's an attempt at a mixed address (IPv6 +
					// IPv4)
					// - if ($addressLiteral[$index - 1] !== ':') return
					// IsEMailResult.ISEMAIL_IPV4BADPREFIX; // Character
					// preceding IPv4 address must be ':'
					// revision 2.1: new IPv6 testing strategy
					if (!PHPFunctions.substr(addressLiteral, 0, 5).equals(
							"IPv6:")) {
						// RFC5321 section 4.1.3
						return IsEMailResult.ISEMAIL_IPV6BADPREFIXMIXED;
					}
					// -
					// - $IPv6 = substr($addressLiteral, 5, ($index === 7) ? 2 :
					// $index - 6);
					// - $groupMax = 6;
					// revision 2.1: new IPv6 testing strategy
					IPv6 = PHPFunctions.substr(addressLiteral, 5, index - 5)
							+ "0000:0000"; // Convert IPv4 part to IPv6 format
				}
			} else {
				// It must be an attempt at pure IPv6
				if (!PHPFunctions.substr(addressLiteral, 0, 5).equals("IPv6:")) {
					// RFC5321 section 4.1.3
					return IsEMailResult.ISEMAIL_IPV6BADPREFIX;
				}
				IPv6 = PHPFunctions.substr(addressLiteral, 5);
				// - $groupMax = 8;
				// revision 2.1: new IPv6 testing strategy
			}

			// Revision 2.7: Daniel Marschall's new IPv6 testing strategy
			matchesIP = PHPFunctions.preg_split(colon, IPv6);
			int groupCount = matchesIP.length;
			int index = IPv6.indexOf(double_colon);

			if (index == -1) {
				// We need exactly the right number of groups
				if (groupCount != groupMax) {
					// RFC5321 section 4.1.3
					return IsEMailResult.ISEMAIL_IPV6GROUPCOUNT;
				}
			} else {
				if (index != IPv6.lastIndexOf(double_colon)) {
					// More than one '::'
					return IsEMailResult.ISEMAIL_IPV6DOUBLEDOUBLECOLON;
				}
				if ((index == 0) || (index == (IPv6.length() - 2)))
					groupMax++; // RFC 4291 allows :: at the start or end of an
				// address with 7 other groups in addition
				if (groupCount > groupMax) {
					// Too many IPv6 groups in address
					return IsEMailResult.ISEMAIL_IPV6TOOMANYGROUPS;
				}
				if (groupCount == groupMax) {
					// Eliding a single group with :: is deprecated by RFCs 5321
					// & 5952
					return_status = IsEMailResult.ISEMAIL_SINGLEGROUPELISION;
				}
			}

			// Check for single : at start and end of address
			// Revision 2.7: Daniel Marschall's new IPv6 testing strategy
			if (IPv6.startsWith(colon) && (!IPv6.startsWith(double_colon))) {
				// Address starts with a single colon
				return IsEMailResult.ISEMAIL_IPV6SINGLECOLONSTART;
			}
			if (IPv6.endsWith(colon) && (!IPv6.endsWith(double_colon))) {
				// Address ends with a single colon
				return IsEMailResult.ISEMAIL_IPV6SINGLECOLONEND;
			}

			// Check for unmatched characters
			for (String s : matchesIP) {
				if (!s.matches("^[0-9A-Fa-f]{0,4}$")) {
					return IsEMailResult.ISEMAIL_IPV6BADCHAR;
				}
			}

			// It's a valid IPv6 address, so...
			return return_status;
			// revision 2.1: bug fix: now correctly return warning status

		} else {
			// It's a domain name...

			// The syntax of a legal Internet host name was specified in RFC-952
			// One aspect of host name syntax is hereby changed: the
			// restriction on the first character is relaxed to allow either a
			// letter or a digit.
			// (http://tools.ietf.org/html/rfc1123#section-2.1)
			//
			// NB RFC 1123 updates RFC 1035, but this is not currently apparent
			// from reading RFC 1035.
			//
			// Most common applications, including email and the Web, will
			// generally not
			// permit...escaped strings
			// (http://tools.ietf.org/html/rfc3696#section-2)
			//
			// the better strategy has now become to make the
			// "at least one period" test,
			// to verify LDH conformance (including verification that the
			// apparent TLD name
			// is not all-numeric)
			// (http://tools.ietf.org/html/rfc3696#section-2)
			//
			// Characters outside the set of alphabetic characters, digits, and
			// hyphen MUST NOT appear in domain name
			// labels for SMTP clients or servers
			// (http://tools.ietf.org/html/rfc5321#section-4.1.2)
			//
			// RFC5321 precludes the use of a trailing dot in a domain name for
			// SMTP purposes
			// (http://tools.ietf.org/html/rfc5321#section-4.1.2)

			dotArray = PHPFunctions.preg_split(
					"(?m)\\.(?=(?:[^\\\"]*\\\"[^\\\"]*\\\")*(?![^\\\"]*\\\"))",
					domain);
			partLength = 0;
			// Since we use 'element' after the foreach
			// loop let's make sure it has a value
			String lastElement = "";
			// revision 1.13: Line above added because PHPLint now checks for
			// Definitely Assigned Variables

			if (dotArray.length == 1) {
				// The mail host probably isn't a TLD
				return_status = IsEMailResult.ISEMAIL_TLD;
			}
			// version 2.0: downgraded to a warning

			for (String element : dotArray) {
				lastElement = element;
				// Remove any leading or trailing FWS
				String new_element = PHPFunctions.preg_replace("^" + FWS + "|"
						+ FWS + "$", "", element);
				if (!element.equals(new_element)) {
					// FWS is unlikely in the real world
					return_status = IsEMailResult.ISEMAIL_FWS;
				}
				element = new_element;
				// version 2.0: Warning condition added
				int elementLength = element.length();

				// Each dot-delimited component must be of type atext
				// A zero-length element implies a period at the beginning or
				// end of the
				// local part, or two periods together. Either way it's not
				// allowed.
				if (elementLength == 0) {
					// Dots in wrong place
					return IsEMailResult.ISEMAIL_DOMAINEMPTYELEMENT;
				}
				// revision 1.15: Speed up the test and get rid of
				// "uninitialized string offset" notices from PHP

				// Then we need to remove all valid comments (i.e. those at the
				// start or end of the element
				if (element.charAt(0) == '(') {
					// Comments are unlikely in the real world
					return_status = IsEMailResult.ISEMAIL_COMMENTS;
					// version 2.0: Warning condition added
					int indexBrace = element.indexOf(')');
					if (indexBrace != -1) {
						if (PHPFunctions
								.preg_match("(?<!\\\\)[\\(\\)]", PHPFunctions
										.substr(element, 1, indexBrace - 1)) > 0) {
							// revision 1.17: Fixed name of constant (also
							// spotted by turboflash - thanks!)
							// Illegal characters in comment
							return IsEMailResult.ISEMAIL_BADCOMMENT_START;
						}
						element = PHPFunctions.substr(element, indexBrace + 1,
								elementLength - indexBrace - 1);
						elementLength = element.length();
					}
				}

				if (element.charAt(elementLength - 1) == ')') {
					// Comments are unlikely in the real world
					return_status = IsEMailResult.ISEMAIL_COMMENTS;
					// version 2.0: Warning condition added
					int indexBrace = element.lastIndexOf('(');
					if (indexBrace != -1) {
						if (PHPFunctions.preg_match("(?<!\\\\)(?:[\\(\\)])",
								PHPFunctions.substr(element, indexBrace + 1,
										elementLength - indexBrace - 2)) > 0) {
							// revision 1.17: Fixed name of constant (also
							// spotted by turboflash - thanks!)
							// Illegal characters in comment
							return IsEMailResult.ISEMAIL_BADCOMMENT_END;
						}

						element = PHPFunctions.substr(element, 0, indexBrace);
						elementLength = element.length();
					}
				}

				// Remove any leading or trailing FWS around the element (inside
				// any comments)
				new_element = PHPFunctions.preg_replace("^" + FWS + "|" + FWS
						+ "$", "", element);
				if (!element.equals(new_element)) {
					// FWS is unlikely in the real world
					return_status = IsEMailResult.ISEMAIL_FWS;
				}
				element = new_element;
				// version 2.0: Warning condition added

				// What's left counts towards the maximum length for this part
				if (partLength > 0)
					partLength++; // for the dot
				partLength += element.length();

				// The DNS defines domain name syntax very generally -- a
				// string of labels each containing up to 63 8-bit octets,
				// separated by dots, and with a maximum total of 255
				// octets.
				// (http://tools.ietf.org/html/rfc1123#section-6.1.3.5)
				if (elementLength > 63) {
					// Label must be 63 characters or less
					return IsEMailResult.ISEMAIL_DOMAINELEMENTTOOLONG;
				}

				// Any ASCII graphic (printing) character other than the
				// at-sign ("@"), backslash, double quote, comma, or square
				// brackets may
				// appear without quoting. If any of that list of excluded
				// characters
				// are to appear, they must be quoted
				// (http://tools.ietf.org/html/rfc3696#section-3)
				//
				// If the hyphen is used, it is not permitted to appear at
				// either the beginning or end of a label.
				// (http://tools.ietf.org/html/rfc3696#section-2)
				//
				// Any excluded characters? i.e. 0x00-0x20, (, ), <, >, [, ], :,
				// ;, @, \, comma, period, "

				if (PHPFunctions.preg_match(
						"[\\x00-\\x20\\(\\)<>\\[\\]:;@\\\\,\\.\"]|^-|-$",
						element) > 0) {
					// Illegal character in domain name
					return IsEMailResult.ISEMAIL_DOMAINBADCHAR;
				}
			}

			if (partLength > 255) {
				// Domain part must be 255 characters or less
				// (http://tools.ietf.org/html/rfc1123#section-6.1.3.5)
				return IsEMailResult.ISEMAIL_DOMAINTOOLONG;
			}

			if (PHPFunctions.preg_match("^[0-9]+$", lastElement) > 0) {
				// TLD probably isn't all-numeric
				// (http://www.apps.ietf.org/rfc/rfc3696.html#sec-2)
				return_status = IsEMailResult.ISEMAIL_TLDNUMERIC;
				// version 2.0: Downgraded to a warning
			}

			// Check DNS?
			if ((checkDNS) && (return_status == IsEMailResult.ISEMAIL_VALID)) {
				// An A-record is not required unless there are no MX-records
				// for a domain. Obvious when you think about it.
				// (http://tools.ietf.org/html/rfc5321#section-5)
				// (http://tools.ietf.org/html/rfc2181#section-10.3)
				// (http://tools.ietf.org/html/rfc1035)
				if (!DNSLookup.hasRecords(domain, "MX")) {
					if (!DNSLookup.hasRecords(domain, "A")) {
						// Neither MX- nor A-record for domain can be found
						return_status = IsEMailResult.ISEMAIL_DOMAINNOTFOUND;
					} else {
						// MX-record for domain can't be found
						return_status = IsEMailResult.ISEMAIL_MXNOTFOUND;
					}
				}
			}
		}

		// Eliminate all other factors, and the one which remains must be the
		// truth. (Sherlock Holmes, The Sign of Four)
		return return_status;// version 2.0: return warning if one is set
	}

	/**
	 * Replaces a char in a String
	 * 
	 * @param s
	 *            The input string
	 * @param pos
	 *            The position of the char to be replaced
	 * @param c
	 *            The new char
	 * @return The new String
	 * @see Source: http://www.rgagnon.com/javadetails/java-0030.html
	 */
	private static String replaceCharAt(String s, int pos, char c) {
		return s.substring(0, pos) + c + s.substring(pos + 1);
	}

	private IsEMail() {
	}
}
