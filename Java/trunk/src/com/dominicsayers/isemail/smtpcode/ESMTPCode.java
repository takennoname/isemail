package com.dominicsayers.isemail.smtpcode;

/**
 * 
 * @author Daniel Marschall
 * @see http://tools.ietf.org/html/rfc3463
 * 
 */
public class ESMTPCode {

	// FIELDS

	private int responseclass;
	private int subject;
	private int detail;

	// CONSTRUCTORS

	public ESMTPCode(int responseclass, int subject, int detail) {
		this.responseclass = responseclass;
		this.subject = subject;
		this.detail = detail;
	}

	public ESMTPCode(ESMTPClass responseclass, int subject, int detail) {
		this.responseclass = responseclass.getCode();
		this.subject = subject;
		this.detail = detail;
	}

	public ESMTPCode(String code) throws ESMTPCodeException {

		String[] ary = code.split("\\.");
		if (ary.length != 3) {
			System.out.println("D");
			throw new ESMTPCodeException();
		}

		int responseclass = Integer.parseInt(ary[0]);
		if (!ESMTPClass.isValid(responseclass)) {
			throw new ESMTPCodeException();
		}

		int subject = Integer.parseInt(ary[1]);
		if ((subject < 0) || (subject > 999)) {
			throw new ESMTPCodeException();
		}

		int detail = Integer.parseInt(ary[1]);
		if ((detail < 0) || (detail > 999)) {
			throw new ESMTPCodeException();
		}

		this.responseclass = responseclass;
		this.subject = subject;
		this.detail = detail;
	}

	// GETTERS

	public int getResponseclass() {
		return responseclass;
	}

	public int getSubject() {
		return subject;
	}

	public int getDetail() {
		return detail;
	}

	// METHODS

	@Override
	public String toString() {
		return responseclass + "." + subject + "." + detail;
	}

}
