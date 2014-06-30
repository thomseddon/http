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
   * @var boolean|string
   */
  public static $baseHeaders = array();

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

}
