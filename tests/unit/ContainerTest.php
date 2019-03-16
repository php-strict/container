<?php
use \PhpStrict\Container\Container;
use \PhpStrict\Container\ContainerInterface;

class ContainerTest extends \Codeception\Test\Unit
{
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
}
