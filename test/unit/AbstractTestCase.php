<?php declare(strict_types=1);

namespace SwoftTest\Auth\Unit;

use PHPUnit\Framework\TestCase;
use Swoft;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\HttpDispatcher;
use Swoft\Stdlib\Helper\ArrayHelper;
use SwoftTest\Http\Server\Testing\MockRequest;
use SwoftTest\Http\Server\Testing\MockResponse;

/**
 * Class AbstractTestCase
 */
class AbstractTestCase extends TestCase
{
    public const ACCEPT_VIEW = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';

    public const ACCEPT_JSON = 'application/json';

    public const ACCEPT_RAW = 'text/plain';

    protected function registerRoute(): void
    {
        /** @var Swoft\Http\Server\Router\Router $router */
        $router = Swoft::getBean('httpRouter');
        $router->get('/', function () {
            return [1];
        });
    }

    /**
     * Send a mock request
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param string $accept
     * @param array  $headers
     * @param string $rawContent
     *
     * @return bool|Response
     * @throws Swoft\Exception\SwoftException
     */
    public function request(
        string $method,
        string $uri,
        array $parameters = [],
        string $accept = self::ACCEPT_JSON,
        array $headers = [],
        string $rawContent = ''
    ) {
        $method         = strtoupper($method);
        $swooleResponse = new MockResponse();
        $swooleRequest  = new MockRequest();
        $this->registerRoute();
        $this->buildMockRequest($method, $uri, $parameters, $accept, $swooleRequest, $headers);

        $swooleRequest->setRawContent($rawContent);

        $request  = Request::loadFromSwooleRequest($swooleRequest);
        $response = new Response($swooleResponse);

        /** @var HttpDispatcher $dispatcher */
        $dispatcher = Swoft::getBean('httpDispatcher');

        $dispatcher->dispatch($request, $response);
        return false;
    }

    /**
     * Send a mock json request
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $headers
     * @param string $rawContent
     *
     * @return bool|\Swoft\Http\Message\Testing\Web\Response
     */
    public function json(
        string $method,
        string $uri,
        array $parameters = [],
        array $headers = [],
        string $rawContent = ''
    ) {
        return $this->request($method, $uri, $parameters, self::ACCEPT_JSON, $headers, $rawContent);
    }

    /**
     * Send a mock view request
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $headers
     * @param string $rawContent
     *
     * @return bool|Response
     * @throws Swoft\Exception\SwoftException
     */
    public function view(
        string $method,
        string $uri,
        array $parameters = [],
        array $headers = [],
        string $rawContent = ''
    ) {
        return $this->request($method, $uri, $parameters, self::ACCEPT_VIEW, $headers, $rawContent);
    }

    /**
     * Send a mock raw content request
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $headers
     * @param string $rawContent
     *
     * @return bool|\Swoft\Http\Message\Testing\Web\Response
     */
    public function raw(
        string $method,
        string $uri,
        array $parameters = [],
        array $headers = [],
        string $rawContent = ''
    ) {
        return $this->request($method, $uri, $parameters, self::ACCEPT_RAW, $headers, $rawContent);
    }

    /**
     * @param string               $method
     * @param string               $uri
     * @param array                $parameters
     * @param string               $accept
     * @param \Swoole\Http\Request $swooleRequest
     * @param array                $headers
     */
    protected function buildMockRequest(
        string $method,
        string $uri,
        array $parameters,
        string $accept,
        &$swooleRequest,
        array $headers = []
    ): void {
        $urlAry    = parse_url($uri);
        $urlParams = [];
        if (isset($urlAry['query'])) {
            parse_str($urlAry['query'], $urlParams);
        }
        $defaultHeaders = [
            'host'                      => '127.0.0.1',
            'connection'                => 'keep-alive',
            'cache-control'             => 'max-age=0',
            'user-agent'                => 'PHPUnit',
            'upgrade-insecure-requests' => '1',
            'accept'                    => $accept,
            'dnt'                       => '1',
            'accept-encoding'           => 'gzip, deflate, br',
            'accept-language'           => 'zh-CN,zh;q=0.8,en;q=0.6,it-IT;q=0.4,it;q=0.2',
        ];

        $swooleRequest->fd     = 1;
        $swooleRequest->header = ArrayHelper::merge($headers, $defaultHeaders);
        $swooleRequest->server = [
            'request_method'     => $method,
            'request_uri'        => $uri,
            'path_info'          => '/',
            'request_time'       => microtime(),
            'request_time_float' => microtime(true),
            'server_port'        => 80,
            'remote_port'        => 54235,
            'remote_addr'        => '10.0.2.2',
            'master_time'        => microtime(),
            'server_protocol'    => 'HTTP/1.1',
            'server_software'    => 'swoole-http-server',
        ];

        if ($method == 'GET') {
            $swooleRequest->get = $parameters;
        } elseif ($method == 'POST') {
            $swooleRequest->post = $parameters;
        }

        if (!empty($urlParams)) {
            $get                = empty($swooleRequest->get) ? [] : $swooleRequest->get;
            $swooleRequest->get = array_merge($urlParams, $get);
        }
    }

    protected function tearDown()
    {
        parent::tearDown();
        swoole_timer_after(1 * 1000, function () {
            swoole_event_exit();
        });
    }

    protected function setCoName($name): String
    {
        $name = "{$name}-co";

        return $name;
    }
}
