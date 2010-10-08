package com.dominicsayers.isemail.examples;

import java.util.Scanner;

import com.dominicsayers.isemail.EMailSyntaxDiagnosis;
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

		EMailSyntaxDiagnosis result = IsEMail.is_email_diagnosis(email,
				CHECK_DNS);

		switch (result.getState()) {
		case OK:
			System.out.println(email + " is a valid email address");
			break;

		case WARNING:
			System.out.println("Warning! " + email
					+ " may not be a real email address!");
			System.out.println("Diagnosis ID:\t\t" + result.getId());
			System.out.println("Diagnosis Label:\t" + result.toString());
			System.out.println("Diagnosis Text:\t\t"
					+ result.getDiagnosisText());

			break;

		case ERROR:
			System.out.println(email + " is NOT a real email address!");
			System.out.println("Diagnosis ID:\t\t" + result.getId());
			System.out.println("Diagnosis Label:\t" + result.toString());
			System.out.println("Diagnosis Text:\t\t"
					+ result.getDiagnosisText());

			break;
		}

	}

}
