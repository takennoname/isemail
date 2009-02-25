<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>is_email() - Run unit tests</title>

<style type="text/css">
p {font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;}
</style>
</head>

<body>
<?php
require_once '..\is_email.php';

function unitTest ($email, $expected, $comment = '') {
	$valid		= is_email($email);
	$not		= ($valid) ? '' : ' not';
	$unexpected	= ($valid !== $expected) ? ' <b>This was unexpected!</b>' : '';
	$comment		= ($comment === '') ? "" : " Comment: $comment";
	
	return "The address <i>$email</i> is$not valid.$unexpected$comment<br />
";
}

echo '<p>' . unitTest("first.last@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("1234567890123456789012345678901234567890123456789012345678901234@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"first last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"first\\\"last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("first\\@last@example.com", false, "Escaping can only happen within a quoted string") . "</p>\n";
echo '<p>' . unitTest("\"first@last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"first\\\\last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("x@x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x234", true, "") . "</p>\n";
echo '<p>' . unitTest("123456789012345678901234567890123456789012345678901234567890@12345678901234567890123456789012345678901234567890123456789.12345678901234567890123456789012345678901234567890123456789.123456789012345678901234567890123456789012345678901234567890123.example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@[12.34.56.78]", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:::12.34.56.78]", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333::4444:12.34.56.78]", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333:4444:5555:6666:12.34.56.78]", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:::1111:2222:3333:4444:5555:6666]", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333::4444:5555:6666]", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333:4444:5555:6666::]", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333:4444:5555:6666:7777:8888]", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@x23456789012345678901234567890123456789012345678901234567890123.example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@1xample.com", true, "") . "</p>\n";
echo '<p>' . unitTest("first.last@123.example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("123456789012345678901234567890123456789012345678901234567890@12345678901234567890123456789012345678901234567890123456789.12345678901234567890123456789012345678901234567890123456789.12345678901234567890123456789012345678901234567890123456789.1234.example.com", false, "Entire address is longer than 256 characters") . "</p>\n";
echo '<p>' . unitTest("first.last", false, "No @") . "</p>\n";
echo '<p>' . unitTest("12345678901234567890123456789012345678901234567890123456789012345@example.com", false, "Local part more than 64 characters") . "</p>\n";
echo '<p>' . unitTest(".first.last@example.com", false, "Local part starts with a dot") . "</p>\n";
echo '<p>' . unitTest("first.last.@example.com", false, "Local part ends with a dot") . "</p>\n";
echo '<p>' . unitTest("first..last@example.com", false, "Local part has consecutive dots") . "</p>\n";
echo '<p>' . unitTest("\"first\"last\"@example.com", false, "Local part contains unescaped excluded characters") . "</p>\n";
echo '<p>' . unitTest("\"first\\last\"@example.com", true, "Any character can be escaped in a quoted string") . "</p>\n";
echo '<p>' . unitTest("\"\"\"@example.com", false, "Local part contains unescaped excluded characters") . "</p>\n";
echo '<p>' . unitTest("\"\\\"@example.com", false, "Local part cannot end with a backslash") . "</p>\n";
echo '<p>' . unitTest("\"\"@example.com", false, "Local part is effectively empty") . "</p>\n";
echo '<p>' . unitTest("first\\\\@last@example.com", false, "Local part contains unescaped excluded characters") . "</p>\n";
echo '<p>' . unitTest("first.last@", false, "No domain") . "</p>\n";
echo '<p>' . unitTest("x@x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456789.x23456", false, "Domain exceeds 255 chars") . "</p>\n";
echo '<p>' . unitTest("first.last@[.12.34.56.78]", false, "Only char that can precede IPv4 address is \':\'") . "</p>\n";
echo '<p>' . unitTest("first.last@[12.34.56.789]", false, "Can\'t be interpreted as IPv4 so IPv6 tag is missing") . "</p>\n";
echo '<p>' . unitTest("first.last@[::12.34.56.78]", false, "IPv6 tag is missing") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv5:::12.34.56.78]", false, "IPv6 tag is wrong") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333::4444:5555:12.34.56.78]", false, "Too many IPv6 groups (4 max)") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333:4444:5555:12.34.56.78]", false, "Not enough IPv6 groups") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333:4444:5555:6666:7777:12.34.56.78]", false, "Too many IPv6 groups (6 max)") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333:4444:5555:6666:7777]", false, "Not enough IPv6 groups") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333:4444:5555:6666:7777:8888:9999]", false, "Too many IPv6 groups (8 max)") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222::3333::4444:5555:6666]", false, "Too many \'::\' (can be none or one)") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333::4444:5555:6666:7777]", false, "Too many IPv6 groups (6 max)") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:333x::4444:5555]", false, "x is not valid in an IPv6 address") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:33333::4444:5555]", false, "33333 is not a valid group in an IPv6 address") . "</p>\n";
echo '<p>' . unitTest("first.last@example.123", false, "TLD can\'t be all digits") . "</p>\n";
echo '<p>' . unitTest("first.last@com", false, "Mail host must be second- or lower level") . "</p>\n";
echo '<p>' . unitTest("first.last@-xample.com", false, "Label can\'t begin with a hyphen") . "</p>\n";
echo '<p>' . unitTest("first.last@exampl-.com", false, "Label can\'t end with a hyphen") . "</p>\n";
echo '<p>' . unitTest("first.last@x234567890123456789012345678901234567890123456789012345678901234.example.com", false, "Label can\'t be longer than 63 octets") . "</p>\n";
echo '<p>' . unitTest("\"Abc\\@def\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Fred\\ Bloggs\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Joe.\\\\Blow\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Abc@def\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Fred Bloggs\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("user+mailbox@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("customer/department=shipping@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\$A12345@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("!def!xyz%abc@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("_somename@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("dclo@us.ibm.com", true, "") . "</p>\n";
echo '<p>' . unitTest("abc\\@def@example.com", false, "This example from RFC3696 was corrected in an erratum") . "</p>\n";
echo '<p>' . unitTest("abc\\\\@example.com", false, "This example from RFC3696 was corrected in an erratum") . "</p>\n";
echo '<p>' . unitTest("peter.piper@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("Doug\\ \\\"Ace\\\"\\ Lovell@example.com", false, "Escaping can only happen in a quoted string") . "</p>\n";
echo '<p>' . unitTest("\"Doug \\\"Ace\\\" L.\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("abc@def@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("abc\\\\@def@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("abc\\@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("@example.com", false, "No local part") . "</p>\n";
echo '<p>' . unitTest("doug@", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("\"qu@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("ote\"@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest(".dot@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("dot.@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("two..dot@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("\"Doug \"Ace\" L.\"@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("Doug\\ \\\"Ace\\\"\\ L\\.@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("hello world@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("gatsby@f.sc.ot.t.f.i.tzg.era.l.d.", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("test@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("TEST@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("1234567890@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("test+test@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("test-test@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("t*est@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("+1~1+@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("{_test_}@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"[[ test ]]\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("test.test@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"test.test\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("test.\"test\"@example.com", true, "Obsolete form, but documented in RFC2822") . "</p>\n";
echo '<p>' . unitTest("\"test@test\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("test@123.123.123.x123", true, "") . "</p>\n";
echo '<p>' . unitTest("test@123.123.123.123", false, "Top Level Domain won\'t be all-numeric (see RFC3696 Section 2). I disagree with Dave Child on this one.") . "</p>\n";
echo '<p>' . unitTest("test@[123.123.123.123]", true, "") . "</p>\n";
echo '<p>' . unitTest("test@example.example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("test@example.example.example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("test.example.com", false, "") . "</p>\n";
echo '<p>' . unitTest("test.@example.com", false, "") . "</p>\n";
echo '<p>' . unitTest("test..test@example.com", false, "") . "</p>\n";
echo '<p>' . unitTest(".test@example.com", false, "") . "</p>\n";
echo '<p>' . unitTest("test@test@example.com", false, "") . "</p>\n";
echo '<p>' . unitTest("test@@example.com", false, "") . "</p>\n";
echo '<p>' . unitTest("-- test --@example.com", false, "No spaces allowed in local part") . "</p>\n";
echo '<p>' . unitTest("[test]@example.com", false, "Square brackets only allowed within quotes") . "</p>\n";
echo '<p>' . unitTest("\"test\\test\"@example.com", true, "Any character can be escaped in a quoted string") . "</p>\n";
echo '<p>' . unitTest("\"test\"test\"@example.com", false, "Quotes cannot be nested") . "</p>\n";
echo '<p>' . unitTest("()[]\\;:,><@example.com", false, "Disallowed Characters") . "</p>\n";
echo '<p>' . unitTest("test@.", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@example.", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@.org", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012.com", false, "255 characters is maximum length for domain. This is 256.") . "</p>\n";
echo '<p>' . unitTest("test@example", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@[123.123.123.123", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@123.123.123.123]", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("NotAnEmail", false, "Phil Haack says so") . "</p>\n";
echo '<p>' . unitTest("@NotAnEmail", false, "Phil Haack says so") . "</p>\n";
echo '<p>' . unitTest("\"test\\\\blah\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"test\\blah\"@example.com", true, "Any character can be escaped in a quoted string") . "</p>\n";
echo '<p>' . unitTest("\"test\\blah\"@example.com", true, "Quoted string specifically excludes carriage returns unless escaped") . "</p>\n";
echo '<p>' . unitTest("\"testblah\"@example.com", False, "Quoted string specifically excludes carriage returns") . "</p>\n";
echo '<p>' . unitTest("\"test\\\"blah\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"test\"blah\"@example.com", false, "Phil Haack says so") . "</p>\n";
echo '<p>' . unitTest("customer/department@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("_Yosemite.Sam@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("~@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest(".wooly@example.com", false, "Phil Haack says so") . "</p>\n";
echo '<p>' . unitTest("wo..oly@example.com", false, "Phil Haack says so") . "</p>\n";
echo '<p>' . unitTest("pootietang.@example.com", false, "Phil Haack says so") . "</p>\n";
echo '<p>' . unitTest(".@example.com", false, "Phil Haack says so") . "</p>\n";
echo '<p>' . unitTest("\"Austin@Powers\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("Ima.Fool@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Ima.Fool\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Ima Fool\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("Ima Fool@example.com", false, "Phil Haack says so") . "</p>\n";
echo '<p>' . unitTest("phil.h\\@\\@ck@haacked.com", false, "Escaping can only happen in a quoted string") . "</p>\n";
echo '<p>' . unitTest("\"first\".\"last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"first\".middle.\"last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"first\\\\\"last\"@example.com", false, "Contains an unescaped quote") . "</p>\n";
echo '<p>' . unitTest("\"first\".last@example.com", true, "obs-local-part form as described in RFC 2822") . "</p>\n";
echo '<p>' . unitTest("first.\"last\"@example.com", true, "obs-local-part form as described in RFC 2822") . "</p>\n";
echo '<p>' . unitTest("\"first\".\"middle\".\"last\"@example.com", true, "obs-local-part form as described in RFC 2822") . "</p>\n";
echo '<p>' . unitTest("\"first.middle\".\"last\"@example.com", true, "obs-local-part form as described in RFC 2822") . "</p>\n";
echo '<p>' . unitTest("\"first.middle.last\"@example.com", true, "obs-local-part form as described in RFC 2822") . "</p>\n";
echo '<p>' . unitTest("\"first..last\"@example.com", true, "obs-local-part form as described in RFC 2822") . "</p>\n";
echo '<p>' . unitTest("\"first\\\"last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"first\\\\\\\"last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("first.\"mid\\dle\".\"last\"@example.com", true, "Backslash can escape anything but must escape something") . "</p>\n";
echo '<p>' . unitTest("\"first\\\\\"last\"@example.com", false, "Contains an unescaped quote") . "</p>\n";
echo '<p>' . unitTest("first.\"\".last@example.com", false, "Contains a zero-length element") . "</p>\n";
echo '<p>' . unitTest("first\\last@example.com", false, "Unquoted string must be an atom") . "</p>\n";
echo '<p>' . unitTest("Abc\\@def@example.com", false, "Was incorrectly given as a valid address in the original RFC3696") . "</p>\n";
echo '<p>' . unitTest("Fred\\ Bloggs@example.com", false, "Was incorrectly given as a valid address in the original RFC3696") . "</p>\n";
echo '<p>' . unitTest("Joe.\\\\Blow@example.com", false, "Was incorrectly given as a valid address in the original RFC3696") . "</p>\n";
echo '<p>' . unitTest("first.last@[IPv6:1111:2222:3333:4444:5555:6666:12.34.567.89]", false, "IPv4 part contains an invalid octet") . "</p>\n";
echo '<p>' . unitTest("\"test\\
 blah\"@example.com", false, "Folding white space can\'t appear within a quoted pair") . "</p>\n";
echo '<p>' . unitTest("\"test
 blah\"@example.com", true, "This is a valid quoted string with folding white space") . "</p>\n";
echo '<p>' . unitTest("{^c\\@**Dog^}@cartoon.com", false, "This is a throwaway example from Doug Lovell\'s article. Actually it\'s not a valid address.") . "</p>\n";
echo '<p>' . unitTest("(foo)cal(bar)@(baz)iamcal.com(quux)", true, "A valid address containing comments") . "</p>\n";
echo '<p>' . unitTest("cal@iamcal(woo).(yay)com", true, "A valid address containing comments") . "</p>\n";
echo '<p>' . unitTest("\"foo\"(yay)@(hoopla)[1.2.3.4]", true, "A valid address containing comments") . "</p>\n";
echo '<p>' . unitTest("cal(woo(yay)hoopla)@iamcal.com", true, "A valid address containing comments") . "</p>\n";
echo '<p>' . unitTest("cal(foo\\@bar)@iamcal.com", true, "A valid address containing comments") . "</p>\n";
echo '<p>' . unitTest("cal(foo\\)bar)@iamcal.com", true, "A valid address containing comments and an escaped parenthesis") . "</p>\n";
echo '<p>' . unitTest("cal(foo(bar)@iamcal.com", false, "Unclosed parenthesis in comment") . "</p>\n";
echo '<p>' . unitTest("cal(foo)bar)@iamcal.com", false, "Too many closing parentheses") . "</p>\n";
echo '<p>' . unitTest("cal(foo\\)@iamcal.com", false, "Backslash at end of comment has nothing to escape") . "</p>\n";
echo '<p>' . unitTest("first()last@example.com", true, "A valid address containing an empty comment") . "</p>\n";
echo '<p>' . unitTest("first(
 middle
 )last@example.com", true, "A valid address containing a comment incorporating Folding White Space") . "</p>\n";
echo '<p>' . unitTest("first(12345678901234567890123456789012345678901234567890)last@(1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890)example.com", false, "Too long with comments, not too long without") . "</p>\n";
?>
</body>

</html>