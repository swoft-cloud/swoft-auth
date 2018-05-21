# Swoft Auth

Swoft Auth Component

## Install

- composer command

```bash
composer require swoft/auth
```

## Document

```php

codeHelper:

/**
 * @ExceptionHandler()
 */
class SwoftExceptionHandler
{

/**
     * @Inject()
     * @var ErrorCodeHelper
     */
    protected $authHelper;


    /**
     * @Handler(AuthException::class)
     * @param Response $response
     * @param \Throwable $t
     * @return Response
     */
    public function handleAuthException(Response $response, \Throwable $t){
        $errorCode = $t->getCode();
        $statusCode = 500;
        $message = $t->getMessage();

        if ($this->authHelper->has($errorCode)) {
            $defaultMessage = $this->authHelper->get($errorCode);
            $statusCode = $defaultMessage['statusCode'];
            if (!$message) {
                $message = $defaultMessage['message'];
            }
        }
        $error = [
            'code' => $errorCode,
            'message' => $message ?: 'Unspecified error',
        ];
        $response = $response->withStatus($statusCode)->json($error);
        return $response;
    }

}
```

## Unit testing

```bash
phpunit
```

## LICENSE

The Component is open-sourced software licensed under the [Apache license](LICENSE).
