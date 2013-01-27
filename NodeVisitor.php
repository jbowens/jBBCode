<?php

namespace JBBCode;

/**
 * Defines an interface for a visitor to traverse the node graph.
 *
 * @author jbowens
 * @since January 2013
 */
interface NodeVisitor {

    abstract function visitDocumentElement(DocumentElement $documentElement);

    abstract function visitTextNode(TextNode $textNode);

    abstract function visitElementNode(ElementNode $elementNode);

}
