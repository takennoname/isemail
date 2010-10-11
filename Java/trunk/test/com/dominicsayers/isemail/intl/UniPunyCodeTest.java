package com.dominicsayers.isemail.intl;

import static org.junit.Assert.*;

import java.net.IDN;

import org.junit.Test;

public class UniPunyCodeTest {

	// Japanese IDN Test TLD
	private static final String ExamplePunycode = "xn--zckzah";
	private static final String ExampleUnicode = IDN.toUnicode(ExamplePunycode);

	@Test
	public void isUnicodeTest() {
		assertFalse(UniPunyCode.isUnicode(ExamplePunycode));
		assertTrue(UniPunyCode.isUnicode(ExampleUnicode));
	}

	@Test
	public void isPunycode() {
		assertTrue(UniPunyCode.isPunycode(ExamplePunycode));
		assertFalse(UniPunyCode.isPunycode(ExampleUnicode));
	}

	@Test
	public void toUnicode() {
		assertEquals(ExampleUnicode, UniPunyCode.toUnicode(ExampleUnicode));
		assertEquals(ExampleUnicode, UniPunyCode.toUnicode(ExamplePunycode));
	}

	@Test
	public void toPunycode() {
		assertEquals(ExamplePunycode, UniPunyCode.toPunycode(ExampleUnicode));
		assertEquals(ExamplePunycode, UniPunyCode.toPunycode(ExamplePunycode));
	}

	@Test
	public void toUnicodeIfPossible() {
		assertEquals(ExampleUnicode, UniPunyCode.toUnicode(ExampleUnicode));
		assertEquals(ExampleUnicode, UniPunyCode.toUnicode(ExamplePunycode));
	}
	
	@Test
	public void toPunycodeIfPossible() {
		assertEquals(ExamplePunycode, UniPunyCode.toPunycode(ExampleUnicode));
		assertEquals(ExamplePunycode, UniPunyCode.toPunycode(ExamplePunycode));
	}

}
