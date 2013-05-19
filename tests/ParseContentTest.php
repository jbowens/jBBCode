<?php

require_once('../Parser.php');

/**
 * Test cases for the code definition parameter that disallows parsing
 * of an element's content.
 *
 * @author jbowens
 */
class ParseContentTest extends PHPUnit_Framework_TestCase {

    /**
     * Tests that when a bbcode is created with parseContent = false, 
     * its contents actually are not parsed.
     */
    public function testSimpleNoParsing() {

        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->addBBCode('verbatim', '{param}', false, false);

        $parser->parse('[verbatim]plain text[/verbatim]');
        $this->assertEquals('plain text', $parser->getAsHtml());

        $parser->parse('[verbatim][b]bold[/b][/verbatim]');
        $this->assertEquals('[b]bold[/b]', $parser->getAsHtml());

    }

    /**
     * Tests that when a tag is not closed within an unparseable tag,
     * the BBCode output does not automatically close that tag (because
     * the contents were not parsed).
     */
    public function testUnclosedTag() {
    
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->addBBCode('verbatim', '{param}', false, false);

        $parser->parse('[verbatim]i wonder [b]what will happen[/verbatim]');
        $this->assertEquals('i wonder [b]what will happen', $parser->getAsHtml());
        $this->assertEquals('[verbatim]i wonder [b]what will happen[/verbatim]', $parser->getAsBBCode());
    }

}
