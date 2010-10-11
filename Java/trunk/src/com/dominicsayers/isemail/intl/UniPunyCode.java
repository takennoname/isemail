package com.dominicsayers.isemail.intl;

import java.net.IDN;

/**
 * Unicode and Punycode functions
 * 
 * @author Daniel Marschall
 * 
 */
public class UniPunyCode {

	/**
	 * Determinates if a given string can be converted into Punycode.
	 * 
	 * @param str
	 *            The string which should be checked
	 * @return Boolean which shows if the string is not yet punicoded.
	 */
	public static boolean isUnicode(String str) {
		if (str == null) {
			return false;
		}
		return (!IDN.toASCII(str).equals(str));
	}

	/**
	 * Determinates if a given string is in Punycode format.
	 * 
	 * @param str
	 *            The string which should be checked
	 * @return Boolean which shows if the string is punycoded or not.
	 */
	public static boolean isPunycode(String str) {
		if (str == null) {
			return false;
		}
		return (!IDN.toUnicode(str).equals(str));
	}

	/**
	 * Converts a given punycoded string into Unicode.
	 * 
	 * @param str
	 *            The string to be converted.
	 * @return The string in Unicode format
	 */
	public static String toUnicode(String str) {
		return IDN.toUnicode(str);
	}

	/**
	 * Converts a given Unicode string into Punycode.
	 * 
	 * @param str
	 *            The string to be converted.
	 * @return The string in Punycode format
	 */
	public static String toPunycode(String str) {
		return IDN.toASCII(str);
	}

	/**
	 * Converts a given string into Unicode if it is coded in Punycode.
	 * 
	 * @param str
	 *            The string to be converted.
	 * @return The string in Unicode format
	 */
	public static String toUnicodeIfPossible(String str) {
		if (isUnicode(str))
			return str;

		return toUnicode(str);
	}
	
	/**
	 * Converts a given string into Punycode if it is coded in Unicode.
	 * 
	 * @param str
	 *            The string to be converted.
	 * @return The string in Punycode format
	 */
	public static String toPunycodeIfPossible(String str) {
		if (isPunycode(str))
			return str;

		return toPunycode(str);
	}

	private UniPunyCode() {
	}

}
