<?php

namespace JBBCode;

require_once "CodeDefinition.php";

/**
 * Implements the builder pattern for the CodeDefinition class. A builder
 * is the recommended way of constructing CodeDefinition objects.
 *
 * @author jbowens
 */
class CodeDefinitionBuilder
{

    protected $tagName;
    protected $useOption = false;
    protected $replacementText;
    protected $parseContent = true;
    protected $nestLimit = -1;
    protected $optionValidator = null;
    protected $bodyValidator = null;

    /**
     * Construct a CodeDefinitionBuilder.
     *
     * @param $tagName  the tag name of the definition to build
     * @param $replacementText  the replacement text of the definition to build
     */
    public function __construct($tagName, $replacementText)
    {
        $this->tagName = $tagName;
        $this->replacementText = $replacementText;
    }

    /**
     * Sets the tag name the CodeDeinition should be built with.
     *
     * @param $tagName  the tag name for the new CodeDefinition
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * Sets the replacement text that the new CodeDefinition should be
     * built with.
     *
     * @param $replacementText  the replacement text for the new CodeDefinition
     */
    public function setReplacementText($replacementText)
    {
        $this->replacementText = $replacementText;
    }

    /**
     * Set whether or not the built CodeDefinition should use the {option} bbcode
     * argument.
     *
     * @param $option  ture iff the definition includes an option
     */
    public function setUseOption($option)
    {
        $this->useOption = $option;
    }

    /**
     * Set whether or not the built CodeDefinition should allow its content
     * to be parsed and evaluated as bbcode.
     *
     * @param $parseContent  true iff the content should be parsed
     */
    public function setParseContent($parseContent)
    {
        $this->parseContent = $parseContent;
    }

    /**
     * Sets the nest limit for this code definition.
     *
     * @param $nestLimit a positive integer, or -1 if there is no limit.
     * @throws InvalidArgumentException  if the nest limit is invalid
     */
    public function setNestLimit($limit)
    {
        if(!is_int($limit) || ($limit <= 0 && -1 != $limit)) {
            throw new InvalidArgumentException("A nest limit must be a positive integer " .
                                               "or -1.");
        }
        $this->nestLimit = $limit;
    }

    /**
     * Sets the InputValidator that option arguments should be validated with.
     *
     * @param $validator  the InputValidator instance to use
     */
    public function setOptionValidator(\JBBCode\InputValidator $validator)
    {
        $this->optionValidator = $validator;
    }

    /**
     * Sets the InputValidator that body ({param}) text should be validated with.
     *
     * @param $validator  the InputValidator instance to use
     */
    public function setBodyValidator(\JBBCode\InputValidator $validator)
    {
        $this->bodyValidator = $validator;
    }

    /**
     * Removes the attached option validator if one is attached.
     */
    public function removeOptionValidator()
    {
        $this->optionValidator = null;
    }

    /**
     * Removes the attached body validator if one is attached.
     */
    public function removeBodyValidator()
    {
        $this->bodyValidator = null;
    }
    
    /**
     * Builds a CodeDefinition with the current state of the builder.
     *
     * @return a new CodeDefinition instance
     */
    public function build()
    {
        $definition = CodeDefinition::construct($this->tagName,
                                                $this->replacementText,
                                                $this->useOption,
                                                $this->parseContent,
                                                $this->nestLimit,
                                                $this->optionValidator,
                                                $this->bodyValidator);
        return $definition;
    }


}
