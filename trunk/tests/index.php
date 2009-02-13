<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>is_email() - Build unit test script from test data</title>
</head>

<body>
<?php
//	Top of PHP script
$php = <<<PHP
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

function unitTest (\$email, \$expected, \$comment = '') {
	\$valid		= is_email(\$email);
	\$not		= (\$valid) ? '' : ' not';
	\$unexpected	= (\$valid !== \$expected) ? ' <b>This was unexpected!</b>' : '';
	\$comment		= (\$comment === '') ? "" : " Comment: \$comment";
	
	return "The address <i>\$email</i> is\$not valid.\$unexpected\$comment<br />\n";
}


PHP;

$document = new DOMDocument();
$document->load('tests.xml');

$testList = $document->getElementsByTagName('test');

for ($i = 0; $i < $testList->length; $i++) {
	$tagList = $testList->item($i)->childNodes;

	unset($address);
	unset($valid);
	unset($comment);

	for ($j = 0; $j < $tagList->length; $j++) {
		$node = $tagList->item($j);
		if ($node->nodeType === XML_ELEMENT_NODE) {
			$name	= $node->nodeName;
			$$name	= $node->nodeValue;
		}
	}

	$expected	= ($valid === 'true') ? true : false; // debug
	$address	= addslashes($address);
	$address	= str_replace('$', '\\$', $address);
	$comment		= addslashes($comment);
	$comment		= str_replace('$', '\\$', $comment);

	$php .= "echo '<p>' . unitTest(\"$address\", $valid, \"$comment\") . \"</p>\\n\";\n";
}

//	Bottom of PHP script
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
