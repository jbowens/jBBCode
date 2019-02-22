<?php

namespace JBBCode;

require_once 'CodeDefinition.php';
require_once 'CodeDefinitionBuilder.php';
require_once 'CodeDefinitionSet.php';
require_once 'validators/CssColorValidator.php';
require_once 'validators/UrlValidator.php';

/**
 * Provides a uppercased default set of common bbcode definitions.
 *
 * @author jbowens|frastel
 */
class UppercasedCodeDefinitionSet implements CodeDefinitionSet
{

    /** @var CodeDefinition[] The default code definitions in this set. */
    protected $definitions = array();

    /**
     * Constructs the default code definitions.
     */
    public function __construct()
    {
        /* [B] bold tag */
        $builder = new CodeDefinitionBuilder('B', '<strong>{param}</strong>');
        $this->definitions[] = $builder->build();

        /* [I] italics tag */
        $builder = new CodeDefinitionBuilder('I', '<em>{param}</em>');
        $this->definitions[] = $builder->build();

        /* [U] underline tag */
        $builder = new CodeDefinitionBuilder('U', '<u>{param}</u>');
        $this->definitions[] = $builder->build();

        $urlValidator = new \JBBCode\validators\UrlValidator();

        /* [URL] link tag */
        $builder = new CodeDefinitionBuilder('URL', '<a href="{param}">{param}</a>');
        $builder->setParseContent(false)->setBodyValidator($urlValidator);
        $this->definitions[] = $builder->build();

        /* [URL=http://example.com] link tag */
        $builder = new CodeDefinitionBuilder('URL', '<a href="{option}">{param}</a>');
        $builder->setUseOption(true)->setParseContent(true)->setOptionValidator($urlValidator);
        $this->definitions[] = $builder->build();

        /* [IMG] image tag */
        $builder = new CodeDefinitionBuilder('IMG', '<img src="{param}" />');
        $builder->setUseOption(false)->setParseContent(false)->setBodyValidator($urlValidator);
        $this->definitions[] = $builder->build();

        /* [IMG=alt text] image tag */
        $builder = new CodeDefinitionBuilder('IMG', '<img src="{param}" alt="{option}" />');
        $builder->setUseOption(true)->setParseContent(false)->setBodyValidator($urlValidator);
        $this->definitions[] = $builder->build();

        /* [COLOR] color tag */
        $builder = new CodeDefinitionBuilder('COLOR', '<span style="color: {option}">{param}</span>');
        $builder->setUseOption(true)->setOptionValidator(new \JBBCode\validators\CssColorValidator());
        $this->definitions[] = $builder->build();
    }

    /**
     * Returns an array of the default code definitions.
     *
     * @return CodeDefinition[]
     */
    public function getCodeDefinitions()
    {
        return $this->definitions;
    }
}
