<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>is_email() - Run unit tests</title>

<style type="text/css">
p {font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;overflow:hidden;}
</style>
</head>

<body>
<?php
require_once '..\is_email.php';

function unitTest ($email, $expected, $reason = '') {
	$valid		= is_email($email);
	$not		= ($valid) ? '' : ' not';
	$unexpected	= ($valid !== $expected) ? ' <b>This was unexpected!</b>' : '';
	$reason		= ($reason === '') ? "" : " Reason: $reason";
	
	return "The address <i>$email</i> is$not valid.$unexpected$reason<br />
";
}

echo '<p>' . unitTest("first.last@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("1234567890123456789012345678901234567890123456789012345678901234@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"first last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"first\\\"last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("first\\@last@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"first@last\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("first\\\\last@example.com", true, "") . "</p>\n";
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
echo '<p>' . unitTest("@example.com", false, "No local part") . "</p>\n";
echo '<p>' . unitTest("12345678901234567890123456789012345678901234567890123456789012345@example.com", false, "Local part more than 64 characters") . "</p>\n";
echo '<p>' . unitTest(".first.last@example.com", false, "Local part starts with a dot") . "</p>\n";
echo '<p>' . unitTest("first.last.@example.com", false, "Local part ends with a dot") . "</p>\n";
echo '<p>' . unitTest("first..last@example.com", false, "Local part has consecutive dots") . "</p>\n";
echo '<p>' . unitTest("\"first\"last\"@example.com", false, "Local part contains unescaped excluded characters") . "</p>\n";
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
echo '<p>' . unitTest("Abc\\@def@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("Fred\\ Bloggs@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("Joe.\\\\Blow@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Abc@def\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Fred Bloggs\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("user+mailbox@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("customer/department=shipping@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\$A12345@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("!def!xyz%abc@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("_somename@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("dclo@us.ibm.com", true, "") . "</p>\n";
echo '<p>' . unitTest("abc\\@def@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("abc\\\\@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("Fred\\ Bloggs@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("Joe.\\\\Blow@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Abc@def\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Fred Bloggs\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("customer/department=shipping@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\$A12345@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("!def!xyz%abc@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("_somename@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("user+mailbox@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("peter.piper@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("Doug\\ \\\"Ace\\\"\\ Lovell@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"Doug \\\"Ace\\\" L.\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("abc@def@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("abc\\\\@def@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("abc\\@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
echo '<p>' . unitTest("@example.com", false, "Doug Lovell says this should fail") . "</p>\n";
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
echo '<p>' . unitTest("test.\"test\"@example.com", false, "Quoted string must be entire local part (RFC2822 Section 3.4.1). I disagree with Dave Child on this one.") . "</p>\n";
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
echo '<p>' . unitTest("\"test\\test\"@example.com", true, "") . "</p>\n";
echo '<p>' . unitTest("\"test\"test\"@example.com", false, "Quotes cannot be nested") . "</p>\n";
echo '<p>' . unitTest("()[]\\;:,><@example.com", false, "Disallowed Characters") . "</p>\n";
echo '<p>' . unitTest("test@.", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@example.", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@.org", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012.com", false, "255 characters is maximum length for domain. This is 256.") . "</p>\n";
echo '<p>' . unitTest("test@example", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@[123.123.123.123", false, "Dave Child says so") . "</p>\n";
echo '<p>' . unitTest("test@123.123.123.123]", false, "Dave Child says so") . "</p>\n";
?>
</body>

</html>