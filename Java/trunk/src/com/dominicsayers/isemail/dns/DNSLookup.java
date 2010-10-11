package com.dominicsayers.isemail.dns;

import java.util.Hashtable;
import javax.naming.*;
import javax.naming.directory.*;

import com.dominicsayers.isemail.intl.UniPunyCode;

/**
 * This class performs DNS lookups.
 * 
 * @author Rgagnon.com and Daniel Marschall
 * @see Source: http://www.rgagnon.com/javadetails/java-0452.html (Modified)
 * @version 2010-10-08
 */
public class DNSLookup {
	/**
	 * Checks if a host name has a valid record.
	 * 
	 * @param hostName
	 *            The hostname
	 * @param dnsType
	 *            The kind of record (A, AAAA, MX, ...)
	 * @return Whether the record is available or not
	 * @throws DNSLookupException
	 *             Appears on a fatal error like dnsType invalid or initial
	 *             context error.
	 */
	public static boolean hasRecords(String hostName, String dnsType)
			throws DNSLookupException {
		return DNSLookup.doLookup(hostName, dnsType) > 0;
	}

	/**
	 * Counts the number of records found for hostname and the specific type.
	 * Outputs 0 if no record is found or -1 if the hostname is unknown invalid!
	 * 
	 * @param hostName
	 *            The hostname
	 * @param dnsType
	 *            The kind of record (A, AAAA, MX, ...)
	 * @return Whether the record is available or not
	 * @throws DNSLookupException
	 *             Appears on a fatal error like dnsType invalid or initial
	 *             context error.
	 */
	public static int doLookup(String hostName, String dnsType)
			throws DNSLookupException {

		// JNDI cannot take two-byte chars, so we convert the hostname into Punycode
		hostName = UniPunyCode.toPunycodeIfPossible(hostName);

		Hashtable<String, String> env = new Hashtable<String, String>();
		env.put("java.naming.factory.initial",
				"com.sun.jndi.dns.DnsContextFactory");

		DirContext ictx;
		try {
			ictx = new InitialDirContext(env);
		} catch (NamingException e) {
			throw new DNSInitialContextException(e);
		}

		Attributes attrs;
		try {
			attrs = ictx.getAttributes(hostName, new String[] { dnsType });
		} catch (NameNotFoundException e) {
			// The hostname was not found or is invalid
			return -1;
		} catch (InvalidAttributeIdentifierException e) {
			// The DNS type is invalid
			throw new InvalidDNSTypeException(e);
		} catch (NamingException e) {
			// Unknown reason
			throw new DNSLookupException(e);
		}

		Attribute attr = attrs.get(dnsType);
		if (attr == null) {
			return 0;
		}
		return attr.size();
	}

	private DNSLookup() {
	}
}