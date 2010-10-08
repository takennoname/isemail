package com.dominicsayers.isemail.examples;

import java.util.Scanner;

import com.dominicsayers.isemail.IsEMailResult;
import com.dominicsayers.isemail.IsEMail;
import com.dominicsayers.isemail.dns.DNSLookupException;

/**
 * A more complex example of IsEMail. The user can input an email address which
 * gets validated. This time, DNS check is enabled and warnings will be
 * evaluated.
 * 
 * @author Daniel Marschall
 * @version 2010-10-08
 */
public class Example2 {

	final static boolean CHECK_DNS = true;

	@SuppressWarnings("deprecation")
	public static void main(String[] args) throws DNSLookupException {
		System.out.println("IsEMail Example (with DNS check)");

		// Let the user input a string

		System.out.println("Please enter an email address");
		Scanner sc = new Scanner(System.in);
		String email = sc.nextLine();

		// check the email address

		IsEMailResult result = IsEMail.is_email_verbose(email,
				CHECK_DNS);

		switch (result.getState()) {
		case OK:
			System.out.println(email + " is a valid email address");
			break;

		case WARNING:
			System.out.println("Warning! " + email
					+ " may not be a real email address!");
			break;

		case ERROR:
			System.out.println(email + " is NOT a real email address!");
			break;
		}

		System.out.println("Result ID:\t\t" + result.getId());
		System.out.println("Result Enumeration:\t" + result.getConstantName());
		System.out.println("Result String:\t" + result.toString());
		System.out.println("Result Text:\t\t"
				+ result.getStatusTextExplanatory());
		System.out.println("SMTP code:\t\t" + result.getSmtpCode());
		System.out.println("SMTP code with text:\t" + result.getSmtpCodeText());
	}

}
