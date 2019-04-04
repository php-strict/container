<?php
use \PhpStrict\Container\Container;
use \PhpStrict\Container\ContainerInterface;
use \PhpStrict\Container\ContainerException;
use \PhpStrict\Container\NotFoundException;

class ClassWithUnpacker
{
    public function unpacker(array $entries): void
    {
        foreach ($entries as $key => $value) {
            $this->$key = $value;
        }
    }
}

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
    
    protected function getFilledContainer(array $data): Container
    {
        $container = new Container($data);
        $container->obj = $data['obj'];
        return $container;
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
        $this->testFields($this->getFilledContainer($data), $data);
    }
    
    public function testHas()
    {
        $data = $this->getDataArray();
        $container = $this->getFilledContainer($data);
        
        foreach ($data as $key => $val) {
            $this->assertTrue($container->has($key));
        }
    }
    
    public function testGet()
    {
        $data = $this->getDataArray();
        $container = $this->getFilledContainer($data);
        
        foreach ($data as $key => $val) {
            $this->assertEquals($val, $container->get($key));
        }
        
        $this->expectedException(
            NotFoundException::class, 
            function() use ($container) {
                $container->get('nonexistence');
            }
        );
    }
    
    public function testSet()
    {
        $data = $this->getDataArray();
        $container = $this->getFilledContainer($data);
        
        foreach ($data as $key => $val) {
            $container->set($key, $val);
            $this->assertEquals($val, $container->$key);
        }
    }
    
    public function testGetRef()
    {
        $container = new Container();
        $obj1 = new stdClass();
        $container->ref =& $obj1;
        
        $obj2 =& $container->getRef('ref');
        $obj2 = new stdClass();
        $this->assertSame($obj1, $obj2);
        
        $obj3 = $container->getRef('ref');
        $obj3 = new stdClass();
        $this->assertNotSame($obj1, $obj3);
    }
    
    public function testSetByRef()
    {
        $container = new Container();
        $obj1 = new stdClass();
        $container->setByRef('ref', $obj1);
        
        $obj2 =& $container->getRef('ref');
        $obj2 = new stdClass();
        $this->assertSame($obj1, $obj2);
        
        $obj3 = $container->getRef('ref');
        $obj3 = new stdClass();
        $this->assertNotSame($obj1, $obj3);
    }
    
    public function testGetAll()
    {
        $data = $this->getDataArray();
        $container = $this->getFilledContainer($data);
        
        $containedData = $container->getAll();
        foreach ($containedData as $ckey => $cval) {
            foreach ($data as $key => $val) {
                if ($key == $ckey) {
                    $this->assertEquals($val, $cval);
                    break;
                }
            }
        }
    }
    
    public function testUnpackWithFunction()
    {
        $data = $this->getDataArray();
        $container = $this->getFilledContainer($data);
        
        $unpackedData = [];
        $unpacker = function(array $entries) use (&$unpackedData) {
            foreach ($entries as $key => $value) {
                $unpackedData[$key] = $value;
            }
        };
        
        $container->unpackWith($unpacker);
        $this->assertEquals($data, $unpackedData);
    }
    
    public function testUnpackWithClass()
    {
        $data = $this->getDataArray();
        $container = $this->getFilledContainer($data);
        
        $objWithUnpacker = new ClassWithUnpacker();
        
        $container->unpackWith([$objWithUnpacker, 'unpacker']);
        $this->assertEquals($data, get_object_vars($objWithUnpacker));
    }
}
