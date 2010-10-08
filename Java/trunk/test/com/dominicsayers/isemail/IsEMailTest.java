package com.dominicsayers.isemail;

import static org.junit.Assert.*;

import java.io.File;
import java.io.IOException;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.junit.Test;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import com.dominicsayers.isemail.dns.DNSLookupException;

/**
 * Tests for the class IsEMailTest.
 * 
 * @author Daniel Marschall
 * @version 2010-10-08
 */

public class IsEMailTest {

	static int errorCount;

	private void checkXML(String xmlFile) throws ParserConfigurationException,
			SAXException, IOException, DNSLookupException {
		File file = new File(xmlFile);
		DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		DocumentBuilder db = dbf.newDocumentBuilder();
		Document doc = db.parse(file);
		doc.getDocumentElement().normalize();
		NodeList nodeLst = doc.getElementsByTagName("test");

		for (int s = 0; s < nodeLst.getLength(); s++) {

			Node fstNode = nodeLst.item(s);

			if (fstNode.getNodeType() == Node.ELEMENT_NODE) {

				Element fstElmnt = (Element) fstNode;

				String id;
				String address;
				boolean expected_valid;
				boolean expected_warning;

				NodeList fstNmElmntLst;
				Element fstNmElmnt;
				NodeList fstNm;
				String cont;

				fstNmElmntLst = fstElmnt.getElementsByTagName("address");
				fstNmElmnt = (Element) fstNmElmntLst.item(0);
				fstNm = fstNmElmnt.getChildNodes();
				try {
					cont = ((Node) fstNm.item(0)).getNodeValue();
				} catch (NullPointerException e) {
					cont = "";
				}
				address = cont;
				address = address.replace("\u2400", "\u0000");

				fstNmElmntLst = fstElmnt.getElementsByTagName("valid");
				fstNmElmnt = (Element) fstNmElmntLst.item(0);
				fstNm = fstNmElmnt.getChildNodes();
				cont = ((Node) fstNm.item(0)).getNodeValue();
				expected_valid = Boolean.parseBoolean(cont);

				fstNmElmntLst = fstElmnt.getElementsByTagName("warning");
				fstNmElmnt = (Element) fstNmElmntLst.item(0);
				fstNm = fstNmElmnt.getChildNodes();
				cont = ((Node) fstNm.item(0)).getNodeValue();
				expected_warning = Boolean.parseBoolean(cont);

				fstNmElmntLst = fstElmnt.getElementsByTagName("id");
				fstNmElmnt = (Element) fstNmElmntLst.item(0);
				fstNm = fstNmElmnt.getChildNodes();
				cont = ((Node) fstNm.item(0)).getNodeValue();
				id = cont;

				EMailSyntaxDiagnosis diagnosis = IsEMail
						.is_email_diagnosis(address, true);
				
				boolean actual_valid = (diagnosis.getState() != GeneralState.ERROR);

				if (expected_valid != actual_valid) {
					System.err.println("Mail Test #" + id + " FAILED (Wrong validity)! '"
							+ address + "' is '" + actual_valid + "' ('" + diagnosis
							+ "') instead of '" + expected_valid + "'!");
					errorCount++;
				}

				boolean actual_warning = (diagnosis.getState() == GeneralState.WARNING);

				if (expected_warning != actual_warning) {
					System.err.println("Mail Test #" + id + " FAILED (Warning wrong)! '"
							+ address + "' is '" + actual_warning + "' ('" + diagnosis
							+ "') instead of '" + expected_warning + "'!");
					errorCount++;
				}
			}
		}
	}

	@Test
	public void performXMLTests() throws SAXException, IOException,
			ParserConfigurationException, DNSLookupException {

		// First: Null-Pointer Test

		IsEMail.is_email(null, true);

		// Now check the XML testcases

		checkXML("test/eMailTests/tests.xml");
		// TODO: checkXML("test/eMailTests/ExperimentalTests.xml");

		if (errorCount > 0) {
			System.err.println("==> " + errorCount + " ERRORS OCCOURED! <==");
			fail();
		}
	}
}
