<?php

class HTTP_Request {

  /**
   * HTTP Method
   *
   * @var string
   */
  private $method;

  /**
   * url
   *
   * @var string
   */
  private $url;

  /**
   * Headers
   *
   * @var array
   */
  private $headers = array();

  /**
   * Query Params
   *
   * @var string
   */
  private $query = array();

  /**
   * Body
   *
   * @var string
   */
  private $body;

  /**
   * Construct an HTTP Instance
   *
   * @param string $method HTTP Method
   * @param string $url    URL to request
   */
  public function __construct($method, $url)
  {
    $this->method($method);
    $this->url($url);
  }

  /**
   * Set HTTP Method
   *
   * @param string $method
   */
  public function method($method)
  {
    $this->method = strtoupper($method);
  }

  /**
   * Set request url
   *
   * @param string $url
   * @return  void
   */
  public function url($url)
  {
    $this->url = $url;
  }

  /**
   * Set header params
   *
   * @param  array  $data
   * @return HTTP
   */
  public function set($data, $value = false)
  {
    if (is_array($data)) {
      $this->headers = array_merge($this->headers, $data);
    } else if ($value === false) {
      list($key, $value) = explode(':', $data);
      $this->headers[$key] = trim($value);
    } else {
      $this->headers[$data] = $value;
    }

    return $this;
  }

  /**
   * Set query params
   *
   * @param  array  $data
   * @return HTTP
   */
  public function query($query)
  {
    $this->query = array_merge($this->query, $query);
    return $this;
  }

  /**
   * Set json body
   *
   * @param  array  $data
   * @return HTTP
   */
  public function json($data)
  {
    $this->body = json_encode($data);
    $this->headers['Content-Type'] = 'application/json';
    $this->headers['Content-Length'] = strlen($this->body);
    return $this;
  }

  /**
   * Execute request
   *
   * @return HTTP_Response
   */
  public function send()
  {
    $url = (HTTP::$baseUrl ?: '') . $this->url;
    if (count($this->query))
      $url .= '?' . http_build_query($this->query);

    $ch = curl_init($url);

    $opts = array();
    switch ($this->method) {
      case 'GET':
        $opts[CURLOPT_HTTPGET] = 1;
        break;
      case 'POST':
      case 'PUT':
      case 'DELETE':
        $opts[CURLOPT_CUSTOMREQUEST] = $this->method;
        $opts[CURLOPT_POSTFIELDS] = $this->body;
        break;
      default:
        throw new Exception('Unsupported method: ' . $this->method);
    }

    $opts[CURLOPT_RETURNTRANSFER] = 1;
    $opts[CURLOPT_CONNECTTIMEOUT] = 30;
    $opts[CURLOPT_TIMEOUT] = 80;

    $headers = array_merge($this->headers, HTTP::$baseHeaders);
    $opts[CURLOPT_HTTPHEADER] = array_map(function ($key, $value) {
      return $key . ': ' . $value;
    }, array_keys($headers), $headers);

    curl_setopt_array($ch, $opts);
    $body = curl_exec($ch);

    $info = curl_getinfo($ch);
    $errno = $body === false ? false : curl_errno($ch);
    $error = $body === false ? false : curl_error($ch);

    curl_close($ch);

    return new HTTP_Response($body, $info, $errno, $error);
  }
}
