<?php namespace Tests\Behat\FlexibleMink\Context\FlexibleContext;

use Behat\Mink\Element\NodeElement;

/**
 * @covers \Behat\FlexibleMink\Context\FlexibleContext::getListOfAllNodeElementParents()
 */
class GetListOfAllNodeElementParentsTest extends FlexibleContextTest
{
    public function testMethodStopAtDoesNotIncludeStopAtElement()
    {
        $grandParentBodyElement = $this->getMock(NodeElement::class, ['getTagName'], [], '', false);
        $parentElement = $this->getMock(NodeElement::class, ['getTagName'], [], '', false);
        $siblingElement = $this->getMock(NodeElement::class, ['getParent'], [], '', false);

        $grandParentBodyElement->method('getTagName')->willReturn('body');

        $parentElement->method('getTagName')->willReturn('div');
        $parentElement->method('getParent')->willReturn($grandParentBodyElement);

        $siblingElement->method('getParent')->willReturn($parentElement);

        $return = $this->invokeMethod(
            $this->flexible_context,
            'getListOfAllNodeElementParents', [$siblingElement, 'body']
        );

        self::assertEquals([$parentElement], $return);
    }
}
