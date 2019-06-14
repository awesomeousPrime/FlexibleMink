<?php namespace Tests\Behat\FlexibleMink\Context\FlexibleContext;

use Behat\FlexibleMink\Context\FlexibleContext;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Session;
use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Instantiates the FlexibleContext so it can be used in Unit Tests functions.
 */
abstract class FlexibleContextTest extends PHPUnit_Framework_TestCase
{
    /** @var Session|PHPUnit_Framework_MockObject_MockObject */
    protected $sessionMock;

    /** @var DocumentElement|PHPUnit_Framework_MockObject_MockObject */
    protected $pageMock;

    /** @var FlexibleContext|PHPUnit_Framework_MockObject_MockObject */
    protected $flexible_context;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->flexible_context = $this->getMock(FlexibleContext::class, ['getSession']);
        $this->sessionMock = $this->getMock(Session::class, ['getPage'], [], '', false);
        $this->pageMock = $this->getMock(DocumentElement::class, ['findAll'], [], '', false);
        $this->flexible_context->method('getSession')->willReturn($this->sessionMock);
        $this->sessionMock->method('getPage')->willReturn($this->pageMock);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->pageMock = null;
        $this->sessionMock = null;
        $this->flexible_context = null;
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     * @return mixed Method return.
     * @throws ReflectionException If the class does not exist.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Call protected/private property of a class.
     *
     * @param object $object Instantiated object that we will access the property.
     * @param string $name Name of the property to access.
     * @return ReflectionProperty
     * @throws ReflectionException If the class or property does not exist.
     */
    public function makePropertyAccessible($object, $name)
    {
        if (!property_exists($object, $name)) {
            throw new InvalidArgumentException(
                "Property $name does not exists on " . get_class($object) . '.'
            );
        }

        $property = new ReflectionProperty($object, $name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * Set value for protected/private property of a class.
     *
     * @param object $object Instantiated object that we will get property from.
     * @param string $property_name Property name to set
     * @param mixed $value Property value to set
     * @throws ReflectionException If the class or property does not exist.
     */
    public function setProperty($object, $property_name, $value)
    {
        $property = $this->makePropertyAccessible($object, $property_name);
        $property->setValue($object, $value);
        $property->setAccessible(false);
    }
}
