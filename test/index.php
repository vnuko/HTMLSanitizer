<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//include sanitizer
include("../src/HTMLSanitizer.php");

//init ne sanitizer object
$sanitizer = new HTMLSanitizer();



//=== TESTS ===



//== TEST 1 ==
$dirty_html = <<<HTML
<script type="text/javascript">alert("Cross-site scripting");</script>
<h1>Test 1</h1>
<a href="#" Onclick="alert('booo'); return false();">Click Here...</a>
<a href="javascript:alert('hooo')">Or Click Here...</a>
<iframe src="http://www.w3schools.com"></iframe>
HTML;

$clean_html = $sanitizer->clean($dirty_html);
testMe($dirty_html, $clean_html);



//== TEST 2 ==
$dirty_html = <<<HTML
<h1>Test 2</h1>
<META HTTP-EQUIV="refresh" CONTENT="0;url=data:text/html;base64,PHNjcmlwdD5hbGVydCgnWFNTJyk8L3NjcmlwdD4K">
<a href="javascript:alert('hooo')" style="javascript:alert('booo')"><img src="javascript:alert('mooo')" alt="picture"/></a>
HTML;

$clean_html = $sanitizer->clean($dirty_html);
testMe($dirty_html, $clean_html);



//== TEST 3 ==
$dirty_html = <<<HTML
<h1>Test 3</h1>
<OBJECT TYPE="text/x-scriptlet" DATA="http://www.php.net/"></OBJECT>
<EMBED SRC="data:image/svg+xml;base64,PHN2ZyB4bWxuczpzdmc9Imh0dH A6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcv MjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hs aW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjAiIHk9IjAiIHdpZHRoPSIxOTQiIGhlaWdodD0iMjAw IiBpZD0ieHNzIj48c2NyaXB0IHR5cGU9InRleHQvZWNtYXNjcmlwdCI+YWxlcnQoIlh TUyIpOzwvc2NyaXB0Pjwvc3ZnPg==" type="image/svg+xml" AllowScriptAccess="always"></EMBED>
<META HTTP-EQUIV="refresh" CONTENT="0;url=data:text/html;base64,PHNjcmlwdD5hbGVydCgnWFNTJyk8L3NjcmlwdD4K">
HTML;

$clean_html = $sanitizer->clean($dirty_html);
testMe($dirty_html, $clean_html);



//=== TESTS END=== 



function testMe($dirty_html, $clean_html)
{
    echo $clean_html . "<br>";
    echo '<span style="color:red">Before: </span>' . htmlspecialchars($dirty_html) . '<br>';
    echo '<span style="color:green">After: </span>'. htmlspecialchars($clean_html) . "<br>";
    echo "<br>";   
}