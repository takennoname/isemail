<?php
/*
Copyright 2009 Dominic Sayers
	dominic_sayers@hotmail.com
	http://www.dominicsayers.com

Version 1.2

This source file is subject to the Common Public Attribution License Version 1.0 (CPAL) license.
The license terms are available through the world-wide-web at http://www.opensource.org/licenses/cpal_1.0
*/
function is_email ($email, $checkDNS = false) {
	//	Check that $email is a valid address. Read the following RFCs to understand the constraints:
	//		(http://tools.ietf.org/html/rfc3696)
	//		(http://tools.ietf.org/html/rfc2822)
	//		(http://tools.ietf.org/html/rfc2821#section-4.5.3)
	//		(http://tools.ietf.org/html/rfc5322#section-3.4.1)
	//		(http://tools.ietf.org/html/rfc5321#section-4.1.3)
	//		(http://tools.ietf.org/html/rfc4291#section-2.2)
	//		(http://tools.ietf.org/html/rfc1123#section-2.1)
	
	//	the upper limit on address lengths should normally be considered to be 256
	//		(http://www.rfc-editor.org/errata_search.php?rfc=3696)
	//		NB I think John Klensin is misreading RFC 2821 and the the limit should actually be 254
	//		However, I will stick to the published number until it is changed.
	//
	//	The maximum total length of a reverse-path or forward-path is 256
	//	characters (including the punctuation and element separators)
	//		(http://tools.ietf.org/html/rfc2821#section-4.5.3)
	if (strlen($email) > 256)	return false;	//	Too long

	//	Contemporary email addresses consist of a "local part" separated from
	//	a "domain part" (a fully-qualified domain name) by an at-sign ("@").
	//		(http://tools.ietf.org/html/rfc3696#section-3)
	$index = strrpos($email,'@');

	if ($index === false)		return false;	//	No at-sign
	if ($index === 0)			return false;	//	No local part
	if ($index > 64)			return false;	//	Local part too long

	$localPart		= substr($email, 0, $index);
	$domain			= substr($email, $index + 1);
	$domainLength	= strlen($domain);
	
	if ($domainLength === 0)	return false;	//	No domain part
	if ($domainLength > 255)	return false;	//	Domain part too long

	//	Let's check the local part for RFC compliance...
	//
	//	local-part      =       dot-atom / quoted-string / obs-local-part
	//	obs-local-part  =       word *("." word)
	//		(http://tools.ietf.org/html/rfc2822#section-3.4.1)
	//
	//	Problem: need to distinguish between "first.last" and "first"."last"
	//	(i.e. one element or two). And I suck at regexes.
	$dotArray	= preg_split('/(\\.|\\r\\n|\\n|\\r)(?=(?:[^\\"]*\\"[^\\"]*\\")*(?![^\\"]*\\"))/m', $localPart);
	foreach ($dotArray as $localElement) {
		//	Each dot-delimited component can be an atom or a quoted string
		//	(because of the obs-local-part provision)
		if (preg_match('/^"(?:.)*"$/', $localElement) > 0) {
			//	Quoted-string tests:
			//
			//	My regex skillz aren't up to distunguishing between \" \\" \\\" \\\\" etc.
			//	So remove all \\ from the string first...
			$localElement = str_replace('\\\\', '', $localElement);
			if (preg_match('/(?<!\\\\|^)"(?!$)|\\\\"$|""/', $localElement) > 0)	return false;	//	" must be escaped, \ must have a partner, "" is too short
		} else {
			//	Unquoted string tests:
			//
			//	Period (".") may...appear, but may not be used to start or end the
			//	local part, nor may two or more consecutive periods appear.
			//		(http://tools.ietf.org/html/rfc3696#section-3)
			//
			//	A zero-length element implies a period at the beginning or end of the
			//	local part, or two periods together. Either way it's not allowed.
			if ($localElement === '')											return false;	//	Dots in wrong place

			//	Any ASCII graphic (printing) character other than the
			//	at-sign ("@"), backslash, double quote, comma, or square brackets may
			//	appear without quoting.  If any of that list of excluded characters
			//	are to appear, they must be quoted
			//		(http://tools.ietf.org/html/rfc3696#section-3)
			//
			//	Any excluded characters? i.e. 0x00-0x20, @, [, ], \, ", <comma>
			if (preg_match('/[\\x00-\\x20@\\[\\]\\\\",]/', $localElement) > 0)	return false;	//	These characters must be in a quoted string
		}
	}

	//	Now let's check the domain part...

	//	The domain name can also be replaced by an IP address in square brackets
	//		(http://tools.ietf.org/html/rfc3696#section-3)
	//		(http://tools.ietf.org/html/rfc5321#section-4.1.3)
	//		(http://tools.ietf.org/html/rfc4291#section-2.2)
	if (preg_match('/^\\[(.)+]$/', $domain) === 1) {
		//	It's an address-literal
		$addressLiteral = substr($domain, 1, $domainLength - 2);
		$matchesIP		= array();
		
		//	Extract IPv4 part from the end of the address-literal (if there is one)
		if (preg_match('/\\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $addressLiteral, $matchesIP) > 0) {
			$index = strrpos($addressLiteral, $matchesIP[0]);
			
			if ($index === 0) {
				//	Nothing there except a valid IPv4 address, so...
				return true;
			} else {
				//	Assume it's an attempt at a mixed address (IPv6 + IPv4)
				if ($addressLiteral[$index - 1] !== ':')		return false;	//	Character preceding IPv4 address must be ':'
				if (substr($addressLiteral, 0, 5) !== 'IPv6:')	return false;	//	RFC5321 section 4.1.3

				$IPv6 = substr($addressLiteral, 5, ($index ===7) ? 2 : $index - 6);
				$groupMax = 6;
			}
		} else {
			//	It must be an attempt at pure IPv6
			if (substr($addressLiteral, 0, 5) !== 'IPv6:')		return false;	//	RFC5321 section 4.1.3
			$IPv6 = substr($addressLiteral, 5);
			$groupMax = 8;
		}

		$groupCount	= preg_match_all('/^[0-9a-fA-F]{0,4}|\\:[0-9a-fA-F]{0,4}|(.)/', $IPv6, $matchesIP);
		$index		= strpos($IPv6,'::');

		if ($index === false) {
			//	We need exactly the right number of groups
			if ($groupCount !== $groupMax)						return false;	//	RFC5321 section 4.1.3
		} else {
			if ($index !== strrpos($IPv6,'::'))					return false;	//	More than one '::'
			$groupMax = ($index === 0 || $index === (strlen($IPv6) - 2)) ? $groupMax : $groupMax - 1;
			if ($groupCount > $groupMax)						return false;	//	Too many IPv6 groups in address
		}

		//	Check for unmatched characters
		array_multisort($matchesIP
[1], SORT_DESC);
		if ($matchesIP[1][0] !== '')							return false;	//	Illegal characters in address

		//	It's a valid IPv6 address, so...
		return true;
	} else {
		//	It's a domain name...

		//	The syntax of a legal Internet host name was specified in RFC-952
		//	One aspect of host name syntax is hereby changed: the
		//	restriction on the first character is relaxed to allow either a
		//	letter or a digit.
		//		(http://tools.ietf.org/html/rfc1123#section-2.1)
		//
		//	NB RFC 1123 updates RFC 1035, but this is not currently apparent from reading RFC 1035.
		//
		//	Most common applications, including email and the Web, will generally not permit...escaped strings
		//		(http://tools.ietf.org/html/rfc3696#section-2)
		//
		//	Characters outside the set of alphabetic characters, digits, and hyphen MUST NOT appear in domain name
		//	labels for SMTP clients or servers
		//		(http://tools.ietf.org/html/rfc5321#section-4.1.2)
		//
		//	RFC5321 precludes the use of a trailing dot in a domain name for SMTP purposes
		//		(http://tools.ietf.org/html/rfc5321#section-4.1.2)
		$matches	= array();
		$groupCount	= preg_match_all('/(?:[0-9a-zA-Z][0-9a-zA-Z-]{0,61}[0-9a-zA-Z]|[a-zA-Z])(?:\\.|$)|(.)/', $domain, $matches);
		$level		= count($matches[0]);

		if ($level == 1)										return false;	//	Mail host can't be a TLD

		$TLD = $matches[0][$level - 1];
		if (substr($TLD, strlen($TLD) - 1, 1) === '.')			return false;	//	TLD can't end in a dot
		if (preg_match('/^[0-9]+$/', $TLD) > 0)					return false;	//	TLD can't be all-numeric

		//	Check for unmatched characters
		array_multisort($matches[1], SORT_DESC);
		if ($matches[1][0] !== '')								return false;	//	Illegal characters in domain, or label longer than 63 characters

		//	Check DNS?
		if ($checkDNS && function_exists('checkdnsrr')) {
			if (!(checkdnsrr($domain, 'A') || checkdnsrr($domain, 'MX'))) {
																return false;	//	Domain doesn't actually exist
			}
		}

		//	Eliminate all other factors, and the one which remains must be the truth.
		//		(Sherlock Holmes, The Sign of Four)
		return true;
	}
}
?>
