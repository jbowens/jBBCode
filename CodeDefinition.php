<?php

namespace JBBCode;

/**
 * @author Jackson Owens
 * 
 * This class represents a BBCode Definition.
 */
class CodeDefinition {

    /* NOTE: THIS PROPERTY SHOULD ALWAYS BE LOWERCASE; USE setTagName() TO ENSURE THIS */
    protected $tagName; 

    /* Whether or not this CodeDefinition uses an option parameter. */
    protected $useOption;

    /* The replacement text to be used for simple CodeDefinitions */
    protected $replacementText;

    /* Whether or not to parse elements of this definition's contents */
    protected $parseContent;

    /* How many of this element type may be nested within each other */
    protected $nestLimit;

    /* How many of this element type have been seen */
    protected $elCounter;
    
    /**
     * Constructs a new CodeDefinition.
     * 
     * You WILL want to override this if you extend this class and your new definition accepts an option, doesn't parse
     * its content, etc.
     */
    public function __construct()
    {
        $this->useOption = false;
        $this->parseContent = true;
        $this->nestLimit = -1;
        $this->elCounter = 0;
    }
    
    /**
     * Accepts an ElementNode that is defined by this CodeDefinition and returns the HTML markup of the element.
     * This is a commonly overridden class for custom CodeDefinitions so that the content can be directly manipulated.
     * 
     * @param el the element to return an html representation of
     * 
     * @return the parsed html of this element (INCLUDING ITS CHILDREN)
     */
    public function asHtml( ElementNode $el )
    {
        $html = $this->getReplacementText();
        
        if( $this->usesOption() )
        {
            $html = str_ireplace('{option}', $el->getAttribute(), $html);
        }
        
        if( $this->parseContent() )
        {
            $content = "";
            foreach( $el->getChildren() as $child )
                $content .= $child->getAsHTML();    
        }
        else
        {
            $content = "";
            foreach( $el->getChildren() as $child )
                $content .= $child->getAsBBCode();
        }
        
        $html = str_ireplace('{param}', $content, $html);
        
        return $html;
        
    }
    
    /**
     * Returns the tag name of this code definition
     * 
     * @return this definition's associated tag name
     */
    public function getTagName()
    {
        return $this->tagName;
    }
    
    /**
     * Returns the replacement text of this code definition. This usually has little, if any meaning if the
     * CodeDefinition class was extended. For default, html replacement CodeDefinitions this returns the html
     * markup for the definition.
     * 
     * @return the replacement text of this CodeDefinition
     */
    public function getReplacementText()
    {
        return $this->replacementText;
    }
    
    /**
     * Returns whether or not this CodeDefinition uses the optional {option}
     * 
     * @return true if this CodeDefinition uses the option, false otherwise
     */
    public function usesOption()
    {
        return $this->useOption;
    }
    
    /**
     * Returns whether or not this CodeDefnition parses elements contained within it, or just treats its children as text.
     * 
     * @return true if this CodeDefinition parses elements contained within itself
     */
    public function parseContent()
    {
        return $this->parseContent;
    }
    
    /**
     * 
     * NOT YET SUPPORTED
     * 
     */
    public function getNestLimit()
    {
        return $this->nestLimit;
    }
    
    /**
     * Sets the tag name of this CodeDefinition
     * 
     * @param the new tag name of this definition
     */
    public function setTagName( $tagName )
    {
        $this->tagName = strtolower($tagName);
    }
    
    /**
     * Sets the html replacement text of this CodeDefinition
     * 
     * @param the new replacement text
     */
    public function setReplacementText( $txt )
    {
        $this->replacementText = $txt;
    }
    
    /**
     * Sets whether or not this CodeDefinition uses the {option}
     * 
     * @param boolean $bool
     */
    public function setUseOption( $bool )
    {
        $this->useOption = $bool;
    }

    /**
     * 
     * Sets whether or not this CodeDefinition allows its children to be parsed as html
     * 
     * @param boolean $bool
     * 
     */
    public function setParseContent( $bool )
    {
        $this->parseContent = $bool;
    }
    
    /**
     * 
     * NOT YET SUPPORTED
     * 
     * @param integer $limit
     * 
     */
    public function setNestLimit( $limit = -1 )
    {
        $this->nestLimit = $limit;
    }
    
    /**
     * 
     * Increments the element counter. This is used for tracking depth of elements of the same type for next limits.
     * 
     * @return void
     * 
     */
    public function incrementCounter()
    {
        $this->elCounter++;
    }
    /**
     * 
     * Decrements the element counter.
     * 
     * @return void
     * 
     */
    public function decrementCounter()
    {
        $this->elCounter--;
    }

    /**
     * 
     * Resets the element counter.
     * 
     */
    public function resetCounter()
    {
        $this->elCounter = 0;
    }
    
    /**
     * 
     * Returns the current value of the element counter.
     * 
     * @return int
     * 
     */
    public function getCounter()
    {
        return $this->elCounter;
    }
}

