<?php

class HTTP_Response {

  /**
   * If the response is an error
   *
   * @var boolean
   */
  private $error = false;

  /**
   * The body of the response
   *
   * @var string
   */
  private $body = false;

  /**
   * The body of the response
   *
   * @var string
   */
  private $rawBody = '';

  /**
   * Request info from curl_getinfo
   *
   * @var array
   */
  private $info = array();

  /**
   * Create a new HTTP_Response instance
   *
   * @param string $ch   Open curl handle
   * @param string $body As returned from curl_exec
   */
  public function __construct($body, $info, $errno = false, $error = false)
  {
    $this->rawBody = $body;
    $this->info = $info;

    if ($body === false) {
      $this->error = true;
    } else {
      $this->parseBody();
    }
  }

  /**
   * If the request yielded an error
   *
   * @return boolean
   */
  public function error()
  {
    return $this->error;
  }

  /**
   * HTTP Status code
   *
   * @return int
   */
  public function status()
  {
    return $this->info['http_code'];
  }

  /**
   * Parsed request body
   *
   * @return string|object
   */
  public function body()
  {
    return $this->body;
  }

  /**
   * Raw request body
   *
   * @return string
   */
  public function __toString()
  {
    return $this->rawBody;
  }

  /**
   * Parse body
   *
   * @return  void
   */
  private function parseBody()
  {
    if ($this->info['content_type'] === 'application/json') {
      $body = json_decode($this->rawBody);

      if ($body === null) {
        $this->error = true;
      } else {
        $this->body = $body;
      }
    } else {
      $this->body = $this->rawBody;
    }
  }
}
