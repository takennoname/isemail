<?php require_once('context/section-context.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html><head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<title><?=$_owner_full_name?> - <?=$_section_title?> - <?php $page_title="Tagging and social software"; echo $page_title; ?></title>

<style type="text/css">
.isemail {
	font-size:12px;
	clear:left;
	margin:0;
	padding:0;
}

.isemail_address {
	line-height:12pt;
	float:left;
	overflow:hidden;
	width:200px;
	margin:0 0 0 8px;
	padding:3px;
}

.isemail_result {
	float:left;
	width:60px;
	margin:0 3px 0 3px;
	padding:3px;
}

.isemail_expected			{background-color:white;}
.isemail_unexpected			{background-color:#FFCCCC;}
.isemail_header				{background-color:#CCCCCC;margin-bottom:3px;}

.isemail_tooltip			{position:relative;}
.isemail_tooltip:hover span	{display: block;}
.isemail_tooltip span {
	display: none;
	position: absolute;
	top: 20px;
	left: 10px;
	padding: 5px;
	z-index: 100;
	background-color: #323232;
	color: #fff;
	width:auto;
	-moz-border-radius: 5px; /* this works only in camino/firefox */
	-webkit-border-radius: 5px; /* this is just for Safari */
}

</style>
<link href="../CSS/style.php" rel="stylesheet" type="text/css" />
<link href="../CSS/layout.php" rel="stylesheet" type="text/css" />

</head>

<body>

<div id="centre_content">
	<h2>RFC-compliant email address validator</h2>
	<p>I've written a PHP function to validate an email address against the various RFCs that specifiy what's valid and what's not. I'm not the first to write such a function and I sure won't be the last.</p>
	<p>I have also collected together some of the other widely-used functions that are in the public domain, along with their test cases. Frankly, I'm surprised that example email addresses from <a href="http://www.apps.ietf.org/rfc/rfc3696.html#sec-3">RFC 3696</a> fail all the other functions apart from mine.</p>
	<p>Additionally, I disagree with a few of the test cases proposed by other authors. I've <a href="#exceptions">listed</a> these exceptions and my reasoning after the results below.</p>
	<h2>What can you do on this page?</h2>
	<p>You can compare the validation functions against all the test cases. You can download your chosen function to use in your project.</p>
	<p>If there's an industrial-strength validation function that I've missed please let me know using the contact channels on the left.</p>

<p class="ramble"><span class="isemail_unexpected">result</span> = unexpected result</p>

<div class="isemail isemail_tooltip">
	<p class="isemail_address"><br />Test address</p>
	<p class="isemail_result isemail_header"><a href="http://code.google.com/p/isemail/source/browse/#svn/trunk">Dominic<br />Sayers</a></p>
	<p class="isemail_result isemail_header"><a href="http://code.google.com/p/php-email-address-validation">Dave<br />Child</a></p>
	<p class="isemail_result isemail_header"><a href="http://code.iamcal.com/php/rfc822/">Cal<br />Henderson</a></p>
	<p class="isemail_result isemail_header"><a href="http://simonslick.com/VEAF/">Simon<br />Slick</a></p>
</div>

<?php
require_once 'is_email.php';				//	Dominic Sayers
require_once 'EmailAddressValidator.php';	//	Dave Child
require_once 'rfc2822.php';					//	Cal Henderson
require_once 'SimonSlick.php';				//	Simon Slick

function htmlResult($result, $expected) {
	$classExpected = ((bool) $result === (bool) $expected) ? "isemail_expected" : "isemail_unexpected";
	$text = ((bool) $result) ? "Valid" : "Invalid";
	return "	<p class=\"isemail_result $classExpected\">$text</p>";
}

$validator = new EmailAddressValidator;

$document = new DOMDocument();
$document->load('tests/tests.xml');

$testList = $document->getElementsByTagName('test');

for ($i = 0; $i < $testList->length; $i++) {
	$tagList = $testList->item($i)->childNodes;

	unset($reason);

	for ($j = 0; $j < $tagList->length; $j++) {
		$node = $tagList->item($j);
		if ($node->nodeType === XML_ELEMENT_NODE) {
			$name	= $node->nodeName;
			$$name	= $node->nodeValue;
		}
	}

	$tooltipText	= (isset($reason)) ? "<br />Reason: $reason" : "";
	$expected		= ($valid === 'true') ? true : false;
	$expectedResult	= ($expected) ? "Valid" : "Invalid";

	echo "<div class=\"isemail isemail_tooltip\">\n";
	echo "	<span><strong>$address</strong><br />Expected result: $expectedResult$tooltipText<br />Source: $source</span>\n";
	echo '	<p class="isemail_address"><a href="' . $sourcelink . '" target="_blank">' . $address . "</a></p>\n";
	
	//	Validation routines
	echo htmlResult(is_email($address),							$expected) . "\n";
	echo htmlResult($validator->check_email_address($address),	$expected) . "\n";
	echo htmlResult(is_valid_email_address($address),			$expected) . "\n";
	echo htmlResult(Validate_Email_Address_Format($address),	$expected) . "\n";
	echo "</div>\n";
}
?>
	<a name="exceptions" />
	<p style="clear:left"?&nbsp;</p>
	<h2>Test cases I disagree with</h2>
	<p>Here are the test cases I think are wrong (in other words, the expected result given by the author is different to mine):</p>
	<p style="margin-bottom:0">\$A12345@example.com</p>
	<p class="ramble">This is one of Doug Lovell's test cases. He suggests it is a valid address. I disagree because it contains an unescaped slash. Perhaps he meant for the slash to escape the $-sign, but this is unnecessary as $ is a legal character in an email address.</p>
	<p class="ramble">Doug's article is also wrong to claim that domain labels must start with an alphabetic character. This was changed in 1989 by <a href="http://tools.ietf.org/html/rfc1123#section-2.1" target="_blank">RFC1123</a> to allow domains such as <a href="http://www.3com.com" target="_blank">3com.com</a>.</p>
	<p style="margin-bottom:0">test."test"@example.com</p>
	<p class="ramble">This is one of Dave Child's test cases and he claims it is a valid address. I don't agree that you can quote part of the string like this. If you read <a href="http://www.apps.ietf.org/rfc/rfc2822.html#sec-3.4.1">RFC2822 Section 3.4.1</a> carefully, the local-part must be <strong>either</strong> a quoted-string or a dot-atom, not both.</p>
	<p style="margin-bottom:0">test@123.123.123.123</p>
	<p class="ramble">Another one of Dave Child's test cases which also turns out to be invalid contrary to his expectation. Looking at <a href="http://www.apps.ietf.org/rfc/rfc3696.html#sec-2">RFC 3696 Section 2</a>, it says "There is an additional rule that essentially requires that top-level domain names not be all-numeric." I'm not sure what authority the author of RFC 3696 is citing here, and it's only an informational RFC, but I think it most unlikely there will ever be all-numeric TLDs.</p>
	<p class="ramble">Sorry Dave, but I'm going with John Klensin here. He did write <a href="http://www.apps.ietf.org/rfc/rfc2821.html">RFC2821</a> after all.</p>
	<p style="margin-bottom:0">"test\test"@example.com</p>
	<p class="ramble">Dave Child expects this to fail - in other words it is an invalid address. I disagree, citing RFC3696 again: "any ASCII character, including control characters, may appear quoted, or in a quoted string". This includes the backslash, so this is a perfectly good email address (although not one I'd recommend you take).</p>
	<p class="thrust mini">
	&lt; Back to <a href="/">Home</a>
	| Blog posts: <a target="_blank" href="http://blog.dominicsayers.com/tag/email-address-validation/">Email address validation</a>
	| <a target="_blank" href="http://blog.dominicsayers.com/tag/code/">Code</a>
	| <a target="_blank" href="http://blog.dominicsayers.com">Latest post</a>
	</p>
</div>

<div id="sidebar_left"> <?php include("../includes/left-sidebar.php"); ?> </div>
<div id="sidebar_right"><?php include("../includes/right-sidebar.php"); ?></div>
<div id="banner">       <?php include("../includes/banner.php"); ?>       </div>

<?php include("../includes/analytics.php"); ?>
</body>
</html>
