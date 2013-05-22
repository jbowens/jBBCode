<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php';

/**
 * Test cases for InputValidators.
 *
 * @author jbowens
 * @since May 2013
 */
class ValidatorTest extends PHPUnit_Framework_TestCase
{

    public function testInvalidOptionUrl() {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[url=javascript:alert("HACKED!");]click me[/url]');
        $this->assertEquals('[url=javascript:alert("HACKED!");]click me[/url]',
                $parser->getAsHtml());
    }

    public function testInvalidBodyUrl() {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[url]javascript:alert("HACKED!");[/url]');
        $this->assertEquals('[url]javascript:alert("HACKED!");[/url]', $parser->getAsHtml());
    }

    public function testValidUrl() {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[url]http://jbbcode.com[/url]');
        $this->assertEquals('<a href="http://jbbcode.com">http://jbbcode.com</a>',
                $parser->getAsHtml());
    }

}
