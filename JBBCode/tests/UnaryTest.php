<?php

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php');
require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'CodeDefinitionBuilder.php');

/**
 * Test cases with unary tags
 *
 * @author lorentzkim
 */
class UnaryTest extends PHPUnit_Framework_TestCase
{
    private function defaultHtmlParse($bbcode)
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

        /* [list] tag */
        $builder = new JBBCode\CodeDefinitionBuilder('list', '<ul>{param}</ul>');
        $builder->setUseOption(false);
        $parser->addCodeDefinition($builder->build());

        /* [*] tag */
        $builder = new JBBCode\CodeDefinitionBuilder('*', '<li>{param}</li>');
        $builder->setUseOption(false)->setUnary(true);
        $parser->addCodeDefinition($builder->build());

        $parser->parse($bbcode);
        return $parser->getAsHTML();
    }

    /**
     * Asserts that the given bbcode matches the given text when
     * the bbcode is run through defaultTextParse
     */
    private function assertHtmlOutput($bbcode, $text)
    {
        $this->assertEquals($text, $this->defaultHtmlParse($bbcode));
    }

    public function testUnaryList()
    {
        $this->assertHtmlOutput('[list][*]a[/list]', '<ul><li>a</li></ul>');
        $this->assertHtmlOutput('[list][*]a[*]b[/list]', '<ul><li>a</li><li>b</li></ul>');
        $this->assertHtmlOutput('[list][*]a[*]b[*]c[/list]', '<ul><li>a</li><li>b</li><li>c</li></ul>');
        $this->assertHtmlOutput('[list][*]a[*]b[*]c[*]d[/list]', '<ul><li>a</li><li>b</li><li>c</li><li>d</li></ul>');
    }
}
