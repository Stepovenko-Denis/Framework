<?php


namespace Tests\Framework\Http;


use Framework\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{

    public function testEmpty()
    {
        $request = new Request();

        $this->assertEquals([], $request->getQueryParams());
        $this->assertNull($request->getParsedBody());
    }

    public function testQueryParams()
    {
        $request = (new Request())->withQueryParams($data = [
            'name' => 'Vasay',
            'age' => 25
        ]);

        $this->assertEquals($data, $request->getQueryParams());
        $this->assertNull($request->getParsedBody());
    }

    public function testParsedBody()
    {
        $request = (new Request())->withParsedBody($data = [
            'name' => 'Vasay',
            'age' => 25
        ]);

        $this->assertEquals([], $request->getQueryParams());
        $this->assertEquals($data, $request->getParsedBody());
    }
}