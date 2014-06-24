
# HTTP

Beautifully simple PHP HTTP client inspired by node's [superagent](https://github.com/visionmedia/superagent).

*Consider this a beta release*

## Installation

Download manually or via composer:

```
composer.phar require thomseddon/http:dev-master
```

Then require

```
# Autoload
require_once 'vendor/autoload.php'

# Or manually
require_once '/path/to/vendor/thomseddon/http/lib/HTTP.php'
```


## Examples

### GET

```php

require_once '/path/to/vendor/thomseddon/http/lib/HTTP.php'

$res = HTTP::get('http://httpbin.org/status/418')->send();

var_dump($res->error());
var_dump($res->status());
var_dump($res->body());
```

Prints:
```
bool(false)
int(418)
string(135) "
    -=[ teapot ]=-

       _...._
     .'  _ _ `.
    | ."` ^ `". _,
    \_;`"---"`|//
      |       ;/
      \_     _/
        `"""`
"
```

### POST JSON

```php
require_once '/path/to/vendor/thomseddon/http/lib/HTTP.php'

$res = HTTP::post('http://httpbin.org/post')->json(array(
  'doing' => 'post'
))->send();

var_dump($res->status());
var_dump($res->body());
```

Prints

```

int(200)
class stdClass#3 (8) {
  ...
  public $json =>
  class stdClass#8 (1) {
    public $doing =>
    string(4) "post"
  }
  ...
}
```

## API

### HTTP

#### HTTP::get(string $url)

Return an API_Request instance with method set to `GET`

#### HTTP::post(string $url)

Return an API_Request instance with method set to `POST`

#### HTTP::put(string $url)

Return an API_Request instance with method set to `PUT`

#### HTTP::del(string $url)

Return an API_Request instance with method set to `DELETE`

#### HTTP::configure(array $config)

Two keys can be set:

- `base` *string* - This string will be prepended to all `url`'s during `send()`
- `headers` *array* - Array of headers that will be sent with all subsequent requests

```php

API::configure(array(
  'base' => 'http://api.mysite.com/v1',
  'headers' => array(
    'User-Agent' => 'Frontend Service (0.1)'
  )
));

API::get('/status'); // GET's http://api.mysite.com/v1/status
```

### HTTP_Request

All methods (except send) return the `$this` and so can be chained for extra fun.

#### method(string $method)

Set HTTP method

#### url(string $url)

Set URL

#### set(mixed $data, mixed $value = false)

Set headers, the following are equivalent:

```php

API::get('http://google.com')->set(array(
  'User-Agent' => 'My Service (0.1)'
));
API::get('http://google.com')->set('User-Agent', 'My Service (0.1)');
API::get('http://google.com')->set('User-Agent: My Service (0.1)');

```

#### query(array $query)

Set query params, expects `key => value` array

#### json(mixed $data)

Set's json body: `json_encodes` data and sets `Content-Type` and `Content-Length` headers

#### send()

Sends the request, returns a `HTTP_Response`

### HTTP Response

#### error()

`bool` indicated if the request was not successful

#### status()

HTTP status code

#### body()

Response body

## Testing

```
phpunit
```

## Author

[Thom Seddon](https://twitter.com/ThomSeddon)

## License

The MIT License

Copyright (c) 2014 Thom Seddon

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
