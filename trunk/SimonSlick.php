<?php 

/*
Copyright © 2007, 2008, Simon Slick.  All Rights Reserved.
*/

/*
Version: 0.0.5
Date: 2/17/2008
*/

/*
RFC  821 - 4.5.3 SIZES
RFC  822 - 6 Address Specification
RFC 1034 - 3.5   Preferred Name Syntax
RFC 1035 - 2.3.1 Preferred Name Syntax, 2.3.4 Size Limits
RFC 1123 - 2.1 Host Names and Numbers
RFC 2373 - 2.2 Text Representation of Addresses (IPv6 Addressing Architecture)
RFC 2821
	- 2.3.10 Mailbox and Address
	- 4.1.3 Address Literals
	- 4.5.3.1 Size Limits and Minimums
RFC 2822
	- 3.4.1. Addr-spec specification
	- 4.4. Obsolete Addressing
RFC 3696
	- 2 Restrictions on Domain (DNS) Names
	- 3 Restrictions on Email Addresses
	- errata

Some RFC requirements and specifications may not be fully met, adhered to, nor implemented by this code.
*/

function Validate_Email_Address_Format($email_address) {

	$Email_Address_Length_RegEx = '^(?=.{6,256}$)';					// Must be at least 6 chars (1@1.2), and not more than 256
	$RegEx = ';' . $Email_Address_Length_RegEx . ';iu';				// Add the pattern delimiters ';'

	if (!preg_match($RegEx, $email_address)) return FALSE;

	$domain_pos = strrpos($email_address, "@") + 1;					// Obtain position of last '@' symbol in the email address
	if (is_bool($domain_pos) && !$domain_pos) return FALSE;				// Email address must contain at least one '@' symbol

	$local_part  = substr($email_address, 0, $domain_pos - 1);			// Get the "local-part"
	$domain_part = substr($email_address, $domain_pos);				// Get the "domain-part"

	if (!Validate_Email_Address_Local_Part_Format($local_part )) return FALSE;	// Validate "local-part"  - return negatory if unsuccessful
	if (!Validate_Domain_Format                  ($domain_part)) return FALSE;	// Validate "domain-part" - return negatory if unsuccessful

	return TRUE;									// All checks were successful - Return Affirmative
}


// Validate email address "local-part" format to RFC's or close proximity thereof

function Validate_Email_Address_Local_Part_Format($email_address_local_part) {

	$Email_Address_Local_Part_UnQuoted_RegEx = "^([a-z0-9!#$%&'*+\-/=?^_`{|}~]|(?<!^)\.(?!\.|$)){1,64}$";
	$Email_Address_Local_Part_Quoted_RegEx   = '^"([\x01-\x08\x0b\x0c\x0e-\x1f\x20\x21\x23-\x5b\x5d-\x7f]|\\\\[\x01-\x09\x0b\x0c\x0e-\x7f]){1,64}"$';

	$RegEx = ';' . $Email_Address_Local_Part_UnQuoted_RegEx . '|' . $Email_Address_Local_Part_Quoted_RegEx . ';iu';	// Add the pattern delimiters ';'

	if (preg_match($RegEx, $email_address_local_part)) 
		return TRUE;					// Return affirmative if successful match

	return FALSE;						// No successful match was made - Return Negatory
}


// Validate domain name format or address litteral format for IPv4, IPv6 Full, IPv6 Compressed, IPv6v4 Full, IPv6v4 Compressed

function Validate_Domain_Format($domain) {

	$Domain_Name_RegEx  = '^(?=.{1,255}$)(?!.{1,252}\.([0-9]{2,64}|.)$)([a-z0-9]([a-z0-9\-]{0,61}[a-z0-9])?\.){1,126}([a-z0-9]([a-z0-9\-]{0,61}[a-z0-9])$)';

	$RegEx = '/' . $Domain_Name_RegEx . '/iu';		// Add the pattern delimiters

	if (preg_match($RegEx, $domain)) 
		return TRUE;					// Return affirmative if successful match

	$IPv4_Address_RegEx                = '((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)';	// IPv4

	$IPv6_Standardized_Tag_RegEx       = '[a-z0-9\-]*[a-z0-9]:';
	$IPv6_Full_Address_RegEx           = '[0-9a-f]{1,4}(:[0-9a-f]{1,4}){7}';			// No compression, all 8 groups
	$IPv6v4_Full_Address_RegEx         = '[0-9a-f]{1,4}(:[0-9a-f]{1,4}){5}:' . $IPv4_Address_RegEx;	// No Compression, all 6 groups plus IPv4 address literal

	$IPv6_Compressed_Address_RegEx_1   = '\[([0-9a-f]{1,4}:){1}(:[0-9a-f]{1,4}){1,5}\]';	// Compression :: after 1st group 
	$IPv6_Compressed_Address_RegEx_2   = '\[([0-9a-f]{1,4}:){2}(:[0-9a-f]{1,4}){1,4}\]';	// Compression :: after 2nd group
	$IPv6_Compressed_Address_RegEx_3   = '\[([0-9a-f]{1,4}:){3}(:[0-9a-f]{1,4}){1,3}\]';	// Compression :: after 3rd group
	$IPv6_Compressed_Address_RegEx_4   = '\[([0-9a-f]{1,4}:){4}(:[0-9a-f]{1,4}){1,2}\]';	// Compression :: after 4th group
	$IPv6_Compressed_Address_RegEx_5   = '\[([0-9a-f]{1,4}:){5}(:[0-9a-f]{1,4}){1,1}\]';	// Compression :: after 5th group

	$IPv6_Compressed_Address_RegEx_6   = '\[:(:[0-9a-f]{1,4}){1,6}\]';			// Begins with compression ::
	$IPv6_Compressed_Address_RegEx_7   = '\[([0-9a-f]{1,4}:){1,6}:\]';			// Ends   with compression ::
	$IPv6_Compressed_Address_RegEx_8   = '\[(::)';						// All         compression ::

	$IPv6v4_Compressed_Address_RegEx_1 = '\[([0-9a-f]{1,4}:){1}(:[0-9a-f]{1,4}){1,3}:\]' . $IPv4_Address_RegEx;	// Compression :: after 1st group plus IPv4 address literal
	$IPv6v4_Compressed_Address_RegEx_2 = '\[([0-9a-f]{1,4}:){2}(:[0-9a-f]{1,4}){1,2}:\]' . $IPv4_Address_RegEx;	// Compression :: after 2nd group plus IPv4 address literal
	$IPv6v4_Compressed_Address_RegEx_3 = '\[([0-9a-f]{1,4}:){3}(:[0-9a-f]{1,4}){1,1}:\]' . $IPv4_Address_RegEx;	// Compression :: after 3rd group plus IPv4 address literal

	$IPv6v4_Compressed_Address_RegEx_4 = '\[:(:[0-9a-f]{1,4}){1,4}:\]'                   . $IPv4_Address_RegEx;	// Begins with compression ::     plus IPv4 address literal
	$IPv6v4_Compressed_Address_RegEx_5 = '\[([0-9a-f]{1,4}:){1,4}:\]'                    . $IPv4_Address_RegEx;	// Ends   with compression ::     plus IPv4 address literal
	$IPv6v4_Compressed_Address_RegEx_6 = '\[(::)\]'                                      . $IPv4_Address_RegEx;	// All         compression ::     plus IPv4 address literal

	$IPv6_Address_RegExs_Combined             = "($IPv6_Full_Address_RegEx|$IPv6_Compressed_Address_RegEx_1|$IPv6_Compressed_Address_RegEx_2|$IPv6_Compressed_Address_RegEx_3|$IPv6_Compressed_Address_RegEx_4|$IPv6_Compressed_Address_RegEx_5|$IPv6_Compressed_Address_RegEx_6|$IPv6_Compressed_Address_RegEx_7|$IPv6_Compressed_Address_RegEx_8)";
	$IPv6v4_Address_RegExs_Combined           = "($IPv6v4_Full_Address_RegEx|$IPv6v4_Compressed_Address_RegEx_1|$IPv6v4_Compressed_Address_RegEx_2|$IPv6v4_Compressed_Address_RegEx_3|$IPv6v4_Compressed_Address_RegEx_4|$IPv6v4_Compressed_Address_RegEx_5|$IPv6v4_Compressed_Address_RegEx_6)";
	$IPv6_IPv6v4_Address_RegExs_Combined      = "($IPv6_Address_RegExs_Combined|$IPv6v4_Address_RegExs_Combined)";
	$IPv4_IPv6_IPv6v4_Address_RegExs_Combined = "($IPv4_Address_RegEx|$IPv6_Standardized_Tag_RegEx($IPv6_IPv6v4_Address_RegExs_Combined))";

	$RegEx = '/^\[' . $IPv4_IPv6_IPv6v4_Address_RegExs_Combined . '\]$/iu';	// Add the pattern delimiters '/' and anchors

	if (preg_match($RegEx, $domain)) 
		return TRUE;					// Return affirmative if successful match

	return FALSE;						// No successful match was made - Return Negatory
}

?>