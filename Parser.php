<?php

namespace JBBCode;
use JBBCode\CodeDefinition;

require_once('ElementNode.php');
require_once('TextNode.php');
require_once('DocumentElement.php');
require_once('CodeDefinition.php');
require_once('TokenManager.php');
require_once('NodeVisitor.php');

/**
 * @author Jackson Owens
 * 
 * BBCodeParser is the main parser class that constructs and stores the parse tree. Through this class
 * new bbcode definitions can be added, and documents may be parsed and converted to html/bbcode/plaintext, etc.
 */
class Parser {

    /* The root element of the parse tree */
    protected $treeRoot;

    /* The list of bbcodes to be used by the parser. */
    protected $bbcodes;
    
    /**
     * Constructs an instance of the BBCode parser
     */
    public function __construct() {
        $this->reset();
        $this->bbcodes = array();
    }
    
    /**
     * Adds a simple (text-replacement only) bbcode definition
     * 
     * @param string $tagName   the tag name of the code (for example the b in [b])
     * @param string $replace   the html to use, with {param} and optionally {option} for replacements
     * @param boolean $useOption    whether or not this bbcode uses the secondary {option} replacement
     * @param boolean $parseContent whether or not to parse the content within these elements
     * @param integer $nestLimit    an optional limit of the number of elements of this kind that can be nested within
     *                              each other before the parser stops parsing them.
     */
    public function addBBCode($tagName, $replace, $useOption = false, $parseContent = true, $nestLimit = -1) {
        
        $code = new CodeDefinition();
        $code->setTagName($tagName);
        $code->setUseOption( $useOption );
        $code->setParseContent( $parseContent );
        $code->setNestLimit( $nestLimit );
        $code->setReplacementText($replace);
        
        array_push($this->bbcodes, $code);
        
    }
    
    /**
     * Adds a complex bbcode defnition. You may subclass the CodeDefinition class, instantiate a definition of your new
     * class and add it to the parser through this method.
     * 
     * @param CodeDefinition $definition    the bbcode definition to add
     */
    public function addCodeDefinition( CodeDefinition $definition )
    {   
        array_push($this->bbcodes, $definition);
    }
    
    /**
     * Returns the entire parse tree as text. Only {param} content is returned. BBCode markup will be ignored.
     * 
     * @return a text representation of the parse tree
     */
    public function getAsText() {
        return $this->treeRoot->getAsText();
    }
    
    /**
     * Returns the entire parse tree as bbcode. This will be identical to the inputted string, except unclosed tags
     * will be closed.
     * 
     * @return a bbcode representation of the parse tree
     */
    public function getAsBBCode() {
        return $this->treeRoot->getAsBBCode();
    }
    
    /**
     * Returns the entire parse tree as HTML. All BBCode replacements will be made. This is generally the method
     * you will want to use to retrieve the parsed bbcode.
     * 
     * @return a parsed html string
     */
    public function getAsHTML() {
        return $this->treeRoot->getAsHTML();
    }

    /**
     * Accepts the given NodeVisitor at the root.
     *
     * @param nodeVisitor  a NodeVisitor
     */
    public function accept(NodeVisitor $nodeVisitor) {
        $this->treeRoot->accept($nodeVisitor);
    }
    
    /**
     * Constructs the parse tree from a string of bbcode markup.
     * 
     * @param string $str   the bbcode markup to parse
     */
    public function parse( $str ) {
        
        $this->reset();

        $parent = $this->treeRoot;
        
        $tokenManager = new TokenManager( $str );
                
        $nodeid = 1;
        $inTag = false;
        while( $tokenManager->hasCurrent() ) {
            // tokens are either "[", "]" or a string that contains neither a opening bracket nor a closing bracket
            
            if( $inTag ) {
                // this token should be a tag name
                
                // explode by = in case there's an attribute
                $pieces = explode('=', $tokenManager->getCurrent(), 2);
                
                // check if it's a closing tag
                if( substr($pieces[0], 0, 1) == "/" ) {
                    $tagName = substr($pieces[0], 1);
                    $closing = true;
                } else {
                    $tagName = $pieces[0];
                    $closing = false;
                }
                                                
                if( ($this->codeExists( $tagName, isset($pieces[1])) || $closing && $this->codeExists($tagName, true)) && $tokenManager->hasNext() && $tokenManager->next() == "]" )
                {
                    if( $closing )
                    {
                        $closestParent = $parent->closestParentOfType( $tagName );
                                                
                        if( $closestParent != null && $closestParent->hasParent() )
                        {
                            // closing an element... move to this element's parent
                            $parent->getCodeDefinition()->decrementCounter();
                            $parent = $closestParent->getParent();
                            $tokenManager->advance();
                            $tokenManager->advance();
                            $inTag = false;
                            continue;
                        }
                        
                    } else {
                        // new element
                        $el = new ElementNode();
                        $code = $this->getCode($tagName, isset($pieces[1]));
                        $code->incrementCounter();
                        $el->setNestDepth($code->getCounter());
                        $el->setCodeDefinition($code);
                        $el->setTagName( $tagName );
                        $el->setNodeId( $nodeid++ );
                        if( isset($pieces[1]) )
                            $el->setAttribute( $pieces[1] );
                        
                        $parent->addChild( $el );
                        $parent = $el;
                        $tokenManager->advance();
                        $tokenManager->advance();
                        $inTag = false;
                        continue;
                    }
                }
                
                // the opening bracket that sent us in here was really just plain text
                $node = new TextNode( "[" );
                $node->setNodeId($nodeid++);
                $parent->addChild( $node );
                $inTag = false; 
                // treat this token as regular text, and let the next if...else structure handle it as regular text
                
            }
            
            if( $tokenManager->getCurrent() == "[") {
                $inTag = true;
            }
            else {
                $node = new TextNode( $tokenManager->getCurrent() );
                $node->setNodeId($nodeid++);
                $parent->addChild( $node );
            }
            
            $tokenManager->advance();
            
        }
        
    }
    
    /**
     * Removes any elements that are nested beyond their nest limit from the parse tree.
     */
    public function removeOverNestedElements() {
        foreach( $this->treeRoot->getChildren() as $child )
            $this->removeOverNested($child);
    }
    
    /**
     * Recursive version of removeOverNestedElements().
     *
     * @param a node to clean up (including the entire subtree)
     */
    protected function removeOverNested( Node $el ) {
        if( $el->isTextNode() )
            return;
        else if( $el->beyondDefinitionLimit() )
        {
            $el->getParent()->removeChild( $el );
        }
        else
        {
            foreach( $el->getChildren() as $child )
                $this->removeOverNested($child);
        }
    }
    
    /**
     * Removes the old parse tree if one exists.
     */
    protected function reset() {
        // remove any old tree information
        $this->treeRoot = new DocumentElement();    
    }
    
    /**
     * Determines whether a bbcode exists based on its tag name and whether or not it uses an option
     * 
     * @param string $tagName the bbcode tag name to check
     * @param boolean $usesOption   whether or not the bbcode accepts an option
     * 
     * @return true if the code exists, false otherwise
     */
    public function codeExists( $tagName, $usesOption = false ) {
        foreach( $this->bbcodes as $code )
        {
            if( strtolower($tagName) == $code->getTagName() && $usesOption == $code->usesOption())
                return true;
        }
        return false;
    }
    
    /**
     * Returns the CodeDefinition of a bbcode with the matching tag name and usesOption parameter
     * 
     * @param string $tagName   the tag name of the bbcode being searched for
     * @param boolean $usesOption   whether or not the bbcode accepts an option
     * 
     * @return CodeDefinition   if the bbcode exists, null otherwise
     */
    public function getCode( $tagName, $usesOption = false ) {          
        foreach( $this->bbcodes as $code )
        {
            if( strtolower($tagName) == $code->getTagName() && $code->usesOption() == $usesOption )
                return $code;
        }
        return null;
    }
    
    /**
     * Adds a set of default, standard bbcode definitions commonly used across the web. 
     */
    public function loadDefaultCodes() {
        $this->addBBCode("b", "<strong>{param}</strong>");
        $this->addBBCode("i", "<em>{param}</em>");
        $this->addBBCode("u", "<u>{param}</u>");
        $this->addBBCode("url", "<a href=\"{param}\">{param}</a>");
        $this->addBBCode("url", "<a href=\"{option}\">{param}</a>", true);
        $this->addBBCode("img", "<img src=\"{param}\" alt=\"a user uploaded image\" />");
        $this->addBBCode("img", "<img src=\"{param}\" alt=\"{option}\" />", true);
        $this->addBBCode("color", "<span style=\"color: {option}\">{param}</span>", true);
    }
    
    /**
     * FOR DEBUG ONLY. This method prints the entire parse tree in a human-readable format and kills script execution.
     */
    public function printTree() {
        die("<pre>".htmlentities(print_r($this->treeRoot, true))."</pre>");
    }
    
}

