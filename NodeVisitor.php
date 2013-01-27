<?php

namespace JBBCode;

/**
 * Defines an interface for a visitor to traverse the node graph.
 *
 * @author jbowens
 * @since January 2013
 */
interface NodeVisitor {

    function visitDocumentElement(DocumentElement $documentElement);

    function visitTextNode(TextNode $textNode);

    function visitElementNode(ElementNode $elementNode);

}
