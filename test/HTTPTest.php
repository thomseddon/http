<?php

require __DIR__ . '/../lib/HTTP.php';

class HTTPTest extends PHPUnit_Framework_TestCase
{

  public function testInvalidHost()
  {
    $res = HTTP::get('http://incorrect.wrong')->send();

    $this->assertTrue($res->error());
  }

  public function testUnsupportedMethod()
  {
    $this->setExpectedException('Exception');
    $res = new HTTP_Request('PATCH', '/');
    $res->send();
  }

  public function testGET()
  {
    $res = HTTP::get('http://httpbin.org/status/418')->send();

    $this->assertFalse($res->error());
    $this->assertEquals(418, $res->status());
    $this->assertEquals('
    -=[ teapot ]=-

       _...._
     .\'  _ _ `.
    | ."` ^ `". _,
    \_;`"---"`|//
      |       ;/
      \_     _/
        `"""`
', $res->body());
  }

  public function testParam()
  {
    $res = HTTP::get('http://httpbin.org/get')->query(array(
      'we' => 'will',
      'rock' => 'you'
    ))->send();

    $this->assertEquals(200, $res->status());
    $this->assertEquals((object) array(
      'we' => 'will',
      'rock' => 'you'
    ), $res->body()->args);
  }

  public function testSetString()
  {
    $res = HTTP::get('http://httpbin.org/get')->set('Hello: Goodbye')->send();

    $this->assertEquals(200, $res->status());
    $this->assertObjectHasAttribute('Hello', $res->body()->headers);
    $this->assertEquals('Goodbye', $res->body()->headers->Hello);
  }

  public function testSetKeyValue()
  {
    $res = HTTP::get('http://httpbin.org/get')->set('Hello', 'Goodbye')->send();

    $this->assertEquals(200, $res->status());
    $this->assertObjectHasAttribute('Hello', $res->body()->headers);
    $this->assertEquals('Goodbye', $res->body()->headers->Hello);
  }

  public function testSetArray()
  {
    $res = HTTP::get('http://httpbin.org/get')->set(array(
      'Hello' => 'Goodbye'
    ))->send();

    $this->assertEquals(200, $res->status());
    $this->assertObjectHasAttribute('Hello', $res->body()->headers);
    $this->assertEquals('Goodbye', $res->body()->headers->Hello);
  }

  public function testGETJSONBody()
  {
    $res = HTTP::get('http://httpbin.org/get?it=works')->send();

    $this->assertFalse($res->error());
    $this->assertEquals(200, $res->status());
    $this->assertInstanceOf('stdClass', $res->body());
    $this->assertEquals((object) array(
      'it' => 'works'
    ), $res->body()->args);
  }

  public function testPOSTJSON()
  {
    $res = HTTP::post('http://httpbin.org/post')->json(array(
      'doing' => 'post'
    ))->send();

    $this->assertFalse($res->error());
    $this->assertEquals(200, $res->status());
    $this->assertEquals((object) array(
      'doing' => 'post'
    ), $res->body()->json);
  }

  public function testDELETEJSON()
  {
    $res = HTTP::delete('http://httpbin.org/delete')->json(array(
      'doing' => 'delete'
    ))->send();

    $this->assertFalse($res->error());
    $this->assertEquals(200, $res->status());
    $this->assertEquals((object) array(
      'doing' => 'delete'
    ), $res->body()->json);
  }

  public function testConfigure()
  {
    HTTP::configure(array(
      'base' => 'http://httpbin.org',
      'headers' => array(
        'Doing' => 'tests'
      )
    ));

    $res = HTTP::get('/get')->send();

    $this->assertFalse($res->error());
    $this->assertEquals(200, $res->status());
    $this->assertInstanceOf('stdClass', $res->body());
  }

}
