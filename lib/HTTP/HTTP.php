<?php

class HTTP {

  /**
   * Base URL
   *
   * @var boolean|string
   */
  public static $baseUrl = false;

  /**
   * Base Headers
   *
   * @var array
   */
  public static $baseHeaders = array();

  /**
   * Event Handlers
   *
   * @var array
   */
  public static $handlers = array();

  /**
   * Init a get request
   *
   * @param  string $path
   * @return HTTP_Request
   */
  public static function get($path)
  {
    return new HTTP_Request('GET', $path);
  }

  /**
   * Init a post request
   *
   * @param  string $path
   * @return HTTP_Request
   */
  public static function post($path)
  {
    return new HTTP_Request('POST', $path);
  }

  /**
   * Init a put request
   *
   * @param  string $path
   * @return HTTP_Request
   */
  public static function put($path)
  {
    return new HTTP_Request('PUT', $path);
  }

  /**
   * Init a delete request
   *
   * @param  string $path
   * @return HTTP_Request
   */
  public static function delete($path)
  {
    return new HTTP_Request('DELETE', $path);
  }

  /**
   * Configure HTTP class
   *
   * @param array $config Array of config params
   * @return  void
   */
  public static function configure($config)
  {
    if ($config['base'])
      HTTP::$baseUrl = $config['base'];

    if ($config['headers'])
      array_merge(HTTP::$baseHeaders, $config['headers']);
  }

  /**
   * Add and event handler
   *
   * @param  string   $event   Name of event
   * @param  function $handler [description]
   * @return void
   */
  public static function on($event, $handler)
  {
    if (!array_key_exists($event, HTTP::$handlers))
      HTTP::$handlers[$event] = array();

    HTTP::$handlers[$event][] = $handler;
  }

  /**
   * Emit an event
   *
   * @param  string   $event   Name of event
   * @param  function $handler [description]
   * @return void
   */
  public static function emit($event, $data = null)
  {
    if (!array_key_exists($event, HTTP::$handlers))
      return;


    array_map(function ($handler) use ($data) {
      $handler($data);
    }, HTTP::$handlers[$event]);
  }

}
