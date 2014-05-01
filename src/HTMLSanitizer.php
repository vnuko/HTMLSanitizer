<?php
/**
* The black-list based HTML sanitizer which strips out the javascript code.
* Use to ensure that user provided HTML contains only the plain HTML that 
* you are expecting! This should save your code from cros-site scripting attacks!
*
* HTMLSanitizer parses the user input and then run it through black-list.
* It is assumed that the input HTML is a body fragment; 
* and NOT whole HTML with headers and HTML tags!
*
* @author Milos Vnucko
* @version 0.1.1
*/
class HTMLSanitizer
{
    //global event attributes that can be added to HTML elements to define event actions
    protected $html_events = array(
        'onafterprint', 'onbeforeprint', 'onbeforeunload', 'onerror', 'onhaschange', 'onload', 'onmessage', 'onoffline', 'ononline', 'onpagehide', 'onpageshow',
        'onpopstate', 'onredo', 'onresize', 'onstorage', 'onundo', 'onunload', 'onblur', 'onchange', 'oncontextmenu', 'onfocus', 'onformchange', 'onforminput',
        'oninput', 'oninvalid', 'onreset', 'onselect', 'onsubmit', 'onkeydown', 'onkeypress', 'onkeyup', 'onclick', 'ondblclick', 'ondrag', 'ondragend', 'ondragenter',
        'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onscroll', 'onabort',
        'oncanplay', 'oncanplaythrough', 'ondurationchange', 'onemptied', 'onended', 'onerror', 'onloadeddata', 'onloadedmetadata', 'onloadstart', 'onpause', 'onplay', 
        'onplaying', 'onprogress', 'onratechange', 'onreadystatechange', 'onseeked', 'onseeking', 'onstalled', 'onsuspend', 'ontimeupdate', 'onvolumechange', 'onwaiting'
    );

    protected $clean_string;

    /**
     * Strips out Javascript from HTML input
     * 
     * @param  string $dirty_string
     * 
     * @return string
     */
    public function clean($dirty_string)
    {
        if(empty($dirty_string)) 
            return false;

        $dom = new DOMDocument();
        $dom->loadHTML($dirty_string);

        $nodes = $dom->getElementsByTagName('*');
        foreach($nodes as $node)
        {
            $this->stripJSTags($node)
                ->stripEventAtributes($node)
                ->stripAttributeJSContent($node, 'href')
                ->stripAttributeJSContent($node, 'src')
                ->stripAttributeJSContent($node, 'style');
        }

        $this->clean_string = $this->saveHTMLExact($dom); //save HTML

        return $this->clean_string;
    }

    /**
     * Adds an ability to use the HTMLSanitizer object as a string in a string context.
     * Returns string only when clean() function was used
     * 
     * @return string - String stripped out of Javascript
     */
    public function __toString() 
    {
        return $this->clean_string;
    }

    /**
     * Strips out <script></script> tags
     * 
     * @param  DOMElement $node
     * 
     * @return object HTMLSanitizer
     */
    protected function stripJSTags(DOMElement $node)
    {
        if($node->tagName == 'script')
        {
            $node->parentNode->removeChild($node);
        }

        return $this;
    }

    /**
     * Strips out the HTML event attributes
     * The list of all event attributes is specified in 
     * $html_events array
     * 
     * @param  DOMElement $node
     * 
     * @return object HTMLSanitizer
     */
    protected function stripEventAtributes(DOMElement $node)
    {
        foreach($this->html_events as $event)
        {
            if($node->hasAttribute($event))
                $node->removeAttribute($event);
        }

        return $this;
    }

    /**
     * Strips out the javascript content - 'javascript:*'
     * from specified HTML attribute.
     * 
     * @param  DOMElement $node
     * @param  string     $attr_name attribute name [href, scr, style etc.]
     * 
     * @return object HTMLSanitizer
     */
    protected function stripAttributeJSContent(DOMElement $node, $attr_name)
    {	
        $attr_value = $node->getAttribute($attr_name);
        if($attr_value)
        {
            $match = preg_match('/javascript:/i', $attr_value);
            if($match)
            {
                $node->removeAttribute($attr_name);
                $node->setAttribute($attr_name, '');
            }
        }

        return $this;
    }

    /**
     * Return HTML while stripping the annoying auto-added <html>, <body>, and doctype.
     * This is ok since we assuming that the user input is fragment of body tags
     *
     * @param  DOMDocument $dom
     * @return string
     * 
     * This method was borrowed from SmartDOMDocument class
     * @link http://svn.beerpla.net/repos/public/PHP/SmartDOMDocument/trunk/SmartDOMDocument.class.php
     */
    public function saveHTMLExact(DOMDocument $dom) 
    {
        return preg_replace(array("/^\<\!DOCTYPE.*?<body>/si", "!</body></html>$!si"), "", $dom->saveHTML());
    }
}

