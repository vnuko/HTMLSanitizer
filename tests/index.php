<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../lib/HTMLSanitizer.php");


$sanitizer = new HTMLSanitizer();


//== TEST 1 ==
$html = <<<HTML
<script type="text/javascript">alert("Cross-site scripting");</script>
<h1>Test 1</h1>
<a href="#" onclick="alert('booo'); return false();">Click Here...</a>
<a href="javascript:alert('hooo')">Or Click Here...</a>
HTML;

$clean_html = $sanitizer->clean($html);
echo $clean_html . "<br>";
echo "Before: " . htmlspecialchars($html) . "<br>";
echo "After: " . htmlspecialchars($clean_html) . "<br>";
echo "<br>";


//== TEST 2 ==
$html = <<<HTML
<h1>Test 2</h1>
<a href="javascript:alert('hooo')" style="javascript:alert('booo')"><img src="javascript:alert('mooo')" alt="picture"/></a>
HTML;

$clean_html = $sanitizer->clean($html);
echo $sanitizer . "<br>";
echo "Before: " . htmlspecialchars($html) . "<br>";
echo "After: " . htmlspecialchars($sanitizer) . "<br>";
echo "<br>";
