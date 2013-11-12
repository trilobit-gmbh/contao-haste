<?php

namespace Haste\Test\Http;

include_once __DIR__ . '/../../../../../library/Haste/Http/Response/Response.php';
include_once __DIR__ . '/../../../../../library/Haste/Http/Response/JsonResponse.php';
include_once __DIR__ . '/../../../../../library/Haste/Http/Response/XmlResponse.php';
include_once __DIR__ . '/../../../../../library/Haste/Http/Response/HtmlResponse.php';
include_once __DIR__ . '/../../../../../library/Haste/Util/InsertTag.php';
include_once __DIR__ . '/../../../../../library/Haste/Haste.php';

use Haste\Http\Response\Response;
use Haste\Http\Response\JsonResponse;
use Haste\Http\Response\XmlResponse;
use Haste\Http\Response\HtmlResponse;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $objResponse = new Response('Foobar');
        $this->assertInstanceOf('Haste\Http\Response\Response', $objResponse);
    }

    public function testStatusCode()
    {
        $objResponse = new Response('Foobar');
        $this->assertSame(200, $objResponse->getStatusCode());
        $objResponse = new Response('Foobar', 500);
        $this->assertSame(500, $objResponse->getStatusCode());
    }

    public function testRegularOutput()
    {
        $objResponse = new Response('Foobar');
        $this->assertSame((string) $objResponse, "HTTP/1.1 200 OK\nContent-Type: text/plain; charset=utf-8\nContent-Length: 6\n\nFoobar");
    }

    public function testJsonOutput()
    {
        $objResponse = new JsonResponse(array('foo' => 'bar'), 201);
        $this->assertSame(201, $objResponse->getStatusCode());
        $this->assertSame((string) $objResponse, "HTTP/1.1 201 Created\nContent-Type: application/json; charset=utf-8\nContent-Length: 13\n\n{\"foo\":\"bar\"}");
    }

    public function testEmptyJsonOutput()
    {
        $objResponse = new JsonResponse(array());
        $this->assertSame((string) $objResponse, "HTTP/1.1 200 OK\nContent-Type: application/json; charset=utf-8\nContent-Length: 2\n\n[]");
    }

    public function testXmlOutput()
    {
        $objResponse = new XmlResponse('<foo><bar>indeed</bar></foo>', 304);
        $this->assertSame(304, $objResponse->getStatusCode());
        $this->assertSame((string) $objResponse, "HTTP/1.1 304 Not Modified\nContent-Type: application/xml; charset=utf-8\nContent-Length: 28\n\n<foo><bar>indeed</bar></foo>");
    }

    public function testHtmlOutput()
    {
        $objResponse = new HtmlResponse('<!DOCTYPE html><html lang="en"><head></head><body></body></html>', 100);
        $this->assertSame(100, $objResponse->getStatusCode());
        $this->assertSame((string) $objResponse, "HTTP/1.1 100 Continue\nContent-Type: text/html; charset=utf-8\nContent-Length: 64\n\n<!DOCTYPE html><html lang=\"en\"><head></head><body></body></html>");
    }
}