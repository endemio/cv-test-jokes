<?php

namespace App\Tests;

use App\Exceptions\ErrorDuringResponseToGetCategories;
use App\Service\AbstractMiscService;
use PHPUnit\Framework\TestCase;

class AbstractMiscServiceTest extends TestCase
{
    protected $newAbstractMiscService;

    protected function setUp(): void
    {
        $this->newAbstractMiscService = new class extends AbstractMiscService
        {
            public function returnThis()
            {
                return $this;
            }
        };
    }

    public function testAbstractMiscService()
    {
        $this->assertInstanceOf(
            AbstractMiscService::class,
            $this->newAbstractMiscService->returnThis()
        );

    }

    public function testSendResponse()
    {

        $url = 'https://httpbin.org/status/404';
        $this->expectException(\Exception::class);
        $this->newAbstractMiscService->sendResponse($url);

    }

    public function testSendResponse2()
    {

        $url = 'https://httpbin.org/status/503';
        $this->expectException(\Exception::class);
        $this->newAbstractMiscService->sendResponse($url);

    }

    public function testSendResponse3()
    {
        $url = 'https://httpbin.org/status/304';
        $this->expectException(ErrorDuringResponseToGetCategories::class);
        $this->newAbstractMiscService->sendResponse($url);
    }

    public function testSendResponse4()
    {
        $url = 'https://httpbin.org/anything/bla';
        $this->assertIsString($this->newAbstractMiscService->sendResponse($url));
    }

    public function testGetResultFromResponse(){

        $result = $this->newAbstractMiscService->getResultFromResponse('{"test":4}');
        $this->assertEquals(4,$result['test']);

        $result = $this->newAbstractMiscService->getResultFromResponse('{"bla":55}');
        $this->assertEquals(55,$result['bla']);

        $this->expectException(\Exception::class);
        $this->newAbstractMiscService->getResultFromResponse('{\'bla":55}');
    }


    public function testGetResult(){

        $test_array = ['type'=>'success','value'=>['one','two','three','four'=>['five','six']]];
        $this->assertEquals('one',$this->newAbstractMiscService->getResult($test_array)[0]);
        $this->assertEquals('two',$this->newAbstractMiscService->getResult($test_array)[1]);
        $this->assertEquals('three',$this->newAbstractMiscService->getResult($test_array)[2]);
        $this->assertIsArray($this->newAbstractMiscService->getResult($test_array)['four']);

        // Exceptions
        $test_array = ['type'=>'false','value'=>['one','two','three','four'=>['five','six']]];
        $this->expectException(ErrorDuringResponseToGetCategories::class);
        $this->assertEquals('one',$this->newAbstractMiscService->getResult($test_array));

        $test_array = ['type'=>'success','not_value'=>['one','two','three','four'=>['five','six']]];
        $this->expectException(ErrorDuringResponseToGetCategories::class);
        $this->assertEquals('one',$this->newAbstractMiscService->getResult($test_array));

        $test_array = ['type'=>'success','value'=>'text'];
        $this->expectException(ErrorDuringResponseToGetCategories::class);
        $this->assertEquals('one',$this->newAbstractMiscService->getResult($test_array));

        $test_array = ['type'=>'success'];
        $this->expectException(ErrorDuringResponseToGetCategories::class);
        $this->assertEquals('one',$this->newAbstractMiscService->getResult($test_array));

    }

}
