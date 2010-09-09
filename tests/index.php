<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>devpkg() - Build unit test script from test data</title>
</head>

<body>
<?php
// Top of PHP script
$php = <<<PHP
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>devpkg() - Run unit tests</title>

<style type="text/css">
div {clear:left;}
p {font-family:Segoe UI,Arial,Helvetica,sans-serif;font-size:12px;margin:0;padding:0;float:left;}
p.valid {width:90px;}
p.warning {width:90px;}
p.diagnosis {width:90px;}
p.address {text-align:right;width:400px;overflow:hidden;margin-right:8px;}
p.id {text-align:right;width:40px;overflow:hidden;margin-right:8px;}
p.author {font-style:italic;}
hr {clear:left;}
#conclusion {color:blue;}
</style>
</head>

<body>
<?php
if (file_exists('../devpkg.php')) {
	require_once '../devpkg.php';
} else {
	require_once '../is_email.php';
	function devpkg(\$email, \$checkDNS = false, \$errorlevel = false) {
		return is_email(\$email, \$checkDNS, \$errorlevel);
	}
}
require_once '../extras/is_email_statustext.php';

\$success_count = 0;
\$fail_count = 0;

function unitTest (\$email, \$expected, \$warn_expected, \$comment = '', \$id = '') {
	\$diagnosis	= devpkg(\$email, false, true);
	\$text		= is_email_statustext(\$diagnosis);

	\$warn		= ((\$diagnosis & ISEMAIL_WARNING) !== 0);
	\$valid		= (\$diagnosis < ISEMAIL_ERROR);

	\$warning	= (\$warn) ? 'Yes' : 'No';
	\$result		= (\$valid) ? 'Valid' : 'Not valid';

	\$test_ok = true;
	if (\$valid	!== \$expected) {
		\$result		= "<strong>\$result</strong>";
		\$test_ok = false;
	}
	if (\$warn	!== \$warn_expected) {
		\$warning	= "<strong>\$warning</strong>";
		\$test_ok = false;
	}
	if (\$test_ok) {
		global \$success_count;
		\$success_count++;
	} else {
		global \$fail_count;
		\$fail_count++;
	}
	
	\$comment	= stripslashes(\$comment);

	if (\$text !== '')	\$comment .= (\$comment === '') ? stripslashes(\$text) : ' (' . stripslashes(\$text) . ')';
	if (\$comment === '')	\$comment = "&nbsp;";

	return "<div><p class=\\"address\\"<em>\$email</em></p><p class=\\"id\\">\$id</p><p class=\\"valid\\">\$result</p><p class=\\"warning\\">\$warning</p><p class=\\"diagnosis\\">\$diagnosis</p><p class=\\"comment\\">\$comment</p></div>\\r\\n";
}


PHP;

$document = new DOMDocument();
$document->load('tests.xml');

// Get version
$suite = $document->getElementsByTagName('tests')->item(0);

if ($suite->hasAttribute('version')) {
	$version = $suite->getAttribute('version');
	$php .= "echo \"<h3>Email address validation test suite version $version</h3>\\r\\n\";\r\n";
}

$php .= <<<PHP
echo "<p class=\\"author\\">Dominic Sayers | <a href=\\"mailto:dominic@sayers.cc\\">dominic@sayers.cc</a> | <a href=\\"http://www.dominicsayers.com/isemail\\">RFC-compliant email address validation</a></p>\\r\\n<br>\\r\\n<hr>\\r\\n";
echo "<div><p class=\\"address\\"<strong>Address</strong></p><p class=\\"id\\"><strong>Test #</strong></p><p class=\\"valid\\"><strong>Result</strong></p><p class=\\"warning\\"><strong>Warning</strong></p><p class=\\"diagnosis\\"><strong>Diagnosis</strong></p><p class=\\"comment\\"><strong>Comment</strong></p></div>\\r\\n";
PHP;

$testList = $document->getElementsByTagName('test');

for ($i = 0; $i < $testList->length; $i++) {
	$tagList = $testList->item($i)->childNodes;

	$address	= '';
	$valid		= 'false';
	$warning	= 'false';
	$comment	= '';

	for ($j = 0; $j < $tagList->length; $j++) {
		$node = $tagList->item($j);
		if ($node->nodeType === XML_ELEMENT_NODE) {
			$name	= $node->nodeName;
			$$name	= $node->nodeValue;
		}
	}

//-	$expected	= ($valid === 'true') ? true : false;
	$needles	= array('\\'		, '"'	, '$'	, chr(9)	,chr(10)	,chr(13), '[**NULL**]');
	$substitutes	= array('\\\\'	, '\\"'	, '\\$'	, '\t'		,'\n'		,'\r', '\0');
	$address	= str_replace($needles, $substitutes, $address);
	$comment	= str_replace($needles, $substitutes, $comment);

	$php .= "echo unitTest(\"$address\", $valid, $warning, \"$comment\", \"$id\");\r\n";
}

$php .= "
echo \"<div id=\\\"conclusion\\\"><p><strong>Success = \$success_count<br>Failures = \$fail_count</strong></p></div>\";
";

// Bottom of PHP script
$php .= '?';
$php .= <<<PHP
>
</body>

</html>
PHP;

$handle = @fopen('tests.php', 'wb');
if ($handle === false) die("Can't open tests.php for writing");
fwrite($handle, $php);
fclose($handle);

?>
<p>Successfully created tests.php</p>
<p>Click <a href="tests.php">here</a> to run the tests.</p>
</body>

</html>
