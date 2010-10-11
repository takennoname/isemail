package com.dominicsayers.isemail.dns;

import static org.junit.Assert.*;

import org.junit.Test;

import com.dominicsayers.isemail.dns.DNSLookup;
import com.dominicsayers.isemail.dns.DNSLookupException;
import com.dominicsayers.isemail.dns.InvalidDNSTypeException;

/**
 * Tests for the class MXLookup.
 * 
 * @author Daniel Marschall
 * @version 2010-10-08
 */

public class DNSLookupTest {

	@Test
	public void doLookupTest() throws DNSLookupException {

		// Warning: Test data may change over time!

		try {
			DNSLookup.doLookup("google.de", "");
			fail();
		} catch (InvalidDNSTypeException e) {
			// We expect an InvalidDNSTypeException here
		}

		try {
			DNSLookup.doLookup("google.de", "INVALID_DNS_TYPE");
			fail();
		} catch (InvalidDNSTypeException e) {
			// We expect an InvalidDNSTypeException here
		}

		// host="" is probably the localhost...
		assertEquals(0, DNSLookup.doLookup("", "MX"));

		try {
			DNSLookup.doLookup(null, "MX");
			fail();
		} catch (NullPointerException e) {
			// We expect a NullPointerException here
		}
		
		try {
			DNSLookup.doLookup("google.de", null);
			fail();
		} catch (NullPointerException e) {
			// We expect a NullPointerException here
		}

		// Invalid defined TLD
		assertEquals(-1, DNSLookup.doLookup("invalid", "MX"));

		// Domain not assigned
		assertEquals(-1, DNSLookup.doLookup("viathinkksoft.de", "MX"));

		// Invalid TLD
		assertEquals(-1, DNSLookup.doLookup("yahoo.ccc", "MX"));

		assertEquals(4, DNSLookup.doLookup("google.de", "MX"));
		assertEquals(2, DNSLookup.doLookup("yahoo.de", "MX"));
		assertEquals(2, DNSLookup.doLookup("example.de", "MX"));
		assertEquals(0, DNSLookup.doLookup("example.com", "MX"));
		assertEquals(1, DNSLookup.doLookup("ai", "MX"));
		assertEquals(4, DNSLookup.doLookup("whitehouse.gov", "MX"));

		assertEquals(0, DNSLookup.doLookup("us.ibm.com", "A"));
		assertEquals(18, DNSLookup.doLookup("us.ibm.com", "MX"));
	}
}
