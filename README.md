#HTMLSanitizer
##About
HTMLSanitizer is black-list based HTML sanitizer which strips out the javascript code.

##Usage
	// include HTMLSanitizer class
  include("../src/HTMLSanitizer.php");
	
	$sanitizer = new HTMLSanitizer();
  $clean_html = $sanitizer->clean($dirty_html);
