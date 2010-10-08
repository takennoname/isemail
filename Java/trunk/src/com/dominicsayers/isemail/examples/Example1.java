package com.dominicsayers.isemail.examples;

import com.dominicsayers.isemail.IsEMail;
import com.dominicsayers.isemail.dns.DNSLookupException;

/**
 * Simple usage example is IsEMail. No DNS check and no warnings are getting
 * evaluated.
 * 
 * @author Daniel Marschall
 * @version 2010-10-08
 */
public class Example1 {

	public static void main(String[] args) throws DNSLookupException {
		String email = "dominic@sayers.cc";
		if (IsEMail.is_email(email)) {
			System.out.println(email + " is a valid email address");
		} else {
			System.out.println(email + " is NOT a valid email address");
		}
	}

}
