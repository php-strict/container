<?php
use \PhpStrict\Container\Container;
use \PhpStrict\Container\ContainerInterface;
use \PhpStrict\Container\ContainerException;
use \PhpStrict\Container\NotFoundException;

class ContainerTest extends \Codeception\Test\Unit
{
    /**
     * @param string $expectedExceptionClass
     * @param callable $call = null
     */
    protected function expectedException(string $expectedExceptionClass, callable $call = null)
    {
        try {
            $call();
        } catch (\Exception $e) {
            $this->assertEquals($expectedExceptionClass, get_class($e));
            return;
        }
        if ('' != $expectedExceptionClass) {
            $this->fail('Expected exception not throwed');
        }
    }
    
    /**
     * @return array
     */
    protected function getDataArray(): array
    {
        return [
            'int' => 1,
            'flt' => 2.3,
            'str' => 'test',
            'bln' => true,
            'arr' => ['value1', 'value2', 'value3'],
            'obj' => (object) ['field1' => 'value1', 'field2' => 'value2'],
        ];
    }
    
    protected function testFields(ContainerInterface $container, array $data)
    {
        $this->assertInstanceOf(Container::class, $container);
        $this->assertEquals($data['int'], $container->int);
        $this->assertEquals($data['flt'], $container->flt);
        $this->assertEquals($data['str'], $container->str);
        $this->assertEquals($data['bln'], $container->bln);
        $this->assertCount(count($data['arr']), $container->arr);
        $this->assertEquals($data['arr'], $container->arr);
        $this->assertEquals($data['arr'][1], ($container->arr)[1]);
        $this->assertEquals($data['obj'], $container->obj);
    }
    
    public function testContainer()
    {
        $data = $this->getDataArray();
        $container = new Container($data);
        $container->obj = $data['obj'];
        $this->testFields($container, $data);
    }
    
    public function testHas()
    {
        $data = $this->getDataArray();
        $container = new Container($data);
        $container->obj = $data['obj'];
        
        foreach ($data as $key => $val) {
            $this->assertTrue($container->has($key));
        }
    }
    
    public function testGet()
    {
        $data = $this->getDataArray();
        $container = new Container($data);
        $container->obj = $data['obj'];
        
        foreach ($data as $key => $val) {
            $this->assertEquals($val, $container->get($key));
        }
        
        $this->expectedException(
            NotFoundException::class, 
            function() use ($container) {
                $container->get('unexistence');
            }
        );
    }
}
