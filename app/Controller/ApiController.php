<?php

namespace App\Controller;

use Closure;
use App\Core\Auth;
use App\Model\Post;
use App\Model\User;
use App\Core\Session;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController
{
    protected $data;
    protected $files;
    protected $params;
    protected $resource;
    protected $namespace;
    private $current_call;
    protected $slimRequest;
    protected $slimResponse;
    private const CONTENT_TYPE = 'application/json';
    private const ALLOWED_CONTENT_TYPES = [
        'application/json',
        'application/zip',
        'application/xml',
        'application/xhtml',
        'text/html'
    ];
    private const ALLOWED_HEADERS = [
        'Content-Type', 'Content-Length',
        'Content-Disposition', 'Pragma',
        'Cache-Control', 'Expires', 'Last-Modified'
    ];
    private const API_ROUTE = ROUTES_PATH . DIRECTORY_SEPARATOR . 'api';

    public function __construct(
        private readonly Auth $auth,
        private readonly User $user,
        private readonly Post $post,
        private readonly Session $session
    )
    {
    }
    
    /**
     * Process API request
     */
    public function process(Request $request, Response $response): Response
    {
        $this->files = $_FILES;
        $this->slimRequest = $request;
        $this->slimResponse = $response;
        $this->params = $request->getServerParams();

        $get = $this->cleanInputs($request->getQueryParams());
        $post = $this->cleanInputs($request->getParsedBody() ?? []);
        $this->data = array_merge($get, $post);

        // $this->negotiateHeaders($request->getHeaders());
        // $this->negotiateContentType($request->getHeader('Accept'));
        
        $resource = trim($request->getAttribute('resource'));
        $namespace = trim($request->getAttribute('namespace'));
        $this->namespace = strtolower($namespace);
        $this->resource = strtolower(basename($resource, '.php'));
        
        return $this->handle();
    }

    /**
     * Return API file path if it exists
     */
    protected function fileExists()
    {
        $apiPath = self::API_ROUTE . DIRECTORY_SEPARATOR;
        $filePath = $this->namespace . DIRECTORY_SEPARATOR . $this->resource . '.php';
        $fullPath = $apiPath . $filePath;
        if (file_exists($fullPath)) {
            return $fullPath;
        }
        return false;
    }

    /**
     * Handle API
     */
    protected function handle()
    {
        $func = $this->resource;
        if ($this->fileExists() !== false) {
            include_once $this->fileExists();
            $this->current_call = Closure::bind(${$func}, $this, get_class());
            return $this->$func();
        } else {
            return $this->response([
                'error' => 'method_not_found',
                'resource' => $this->resource
            ], 404);
        }
    }

    /**
     * Check if parameter exists in parsed body
     */
    public function paramsExists(array $params)
    {
        $exists = true;
        if ($this->data == null) {
            return false;
        }
        foreach ($params as $param) {
            if (!array_key_exists($param, $this->data)) {
                $exists = false;
            }
        }
        return $exists;
    }

    /**
     * Clean request inputs
     */
    private function cleanInputs($data)
    {
        $clean_input = array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } else {
            $data = strip_tags($data);
            $clean_input = trim($data);
        }
        return $clean_input;
    }

    public function isXhr()
    {
        $request = $this->slimRequest;
        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return true;
        }
        return false;
    }

    /**
     * Get redirect URL
     */
    public function getRedirect()
    {
        $redirect = $this->session->get('_redirect', '/home');
        $this->session->forget('_redirect');
        return $redirect;
    }

    private function packData(array $data, string $contentType)
    {
        switch ($contentType) {
            case 'application/json':
                return packJson($data);
            case 'application/zip':
                $zipFile = $data['zipFile'];

                if (file_exists($zipFile)) {
                    return file_get_contents($zipFile);
                } else {
                    return packJson(['error' => 'File not found'], 'application/json');
                }
            case 'text/html':
                // Add HTML handling logic here
                break;
                // Add more cases for additional content types
            default:
                return packJson($data);
        }
    }

    private function negotiateContentType(array $acceptedContentTypes): string
    {
        foreach (self::ALLOWED_CONTENT_TYPES as $allowedContentType) {
            if (in_array($allowedContentType, $acceptedContentTypes)) {
                return $allowedContentType;
            }
        }

        throw new InvalidArgumentException('Unsupported Content Type');
    }

    private function negotiateHeaders(array $requestHeaders): array
    {
        $negotiatedHeaders = [];
        foreach ($requestHeaders as $header => $values) {
            $header = ucwords(strtolower($header));
            if (in_array($header, self::ALLOWED_HEADERS)) {
                $negotiatedHeaders[$header] = implode(', ', $values);
            }
        }

        return $negotiatedHeaders;
    }

    public function die()
    {
        $data = [
            "type" => "death"
        ];
        return $this->response($data, 400);
    }

    public function __call($method, $args)
    {
        if (is_callable($this->current_call)) {
            return call_user_func_array($this->current_call, $args);
        } else {
            $error = [
                'error' => 'Method not Allowed',
                'method' => $method
            ];
            $this->response($error, 405);
        }
    }

    /**
     * Return request method
     */
    public function getMethod(): string
    {
        return $this->slimRequest->getMethod();
    }

    /**
     * Check request method
     */
    public function isMethod(string $method): bool
    {
        return strtoupper($this->getMethod()) == strtoupper($method);
    }

    /**
     * Check if user authenticated or not
     */
    public function isAuthenticated(): bool
    {
        return $this->auth->isAuthenticated();
    }

    /**
     * Return user Id from session
     */
    public function getUserId(): string
    {
        return $this->session->get('user');
    }
    
    /**
     * Return JSON Response
     */
    public function response(
        array $payload = [], int $statusCode = 200, 
        $contentType = self::CONTENT_TYPE, array $headers = []
    )
    {
        $this->slimResponse->getBody()->write(
            $this->packData($payload, $contentType)
        );

        if (!empty($headers)) {
            foreach ($headers as $header => $value) {
                $this->slimResponse = $this->slimResponse->withHeader($header, $value);
            }
        }
        
        return $this->slimResponse
            ->withHeader('Content-Type', $contentType)
            ->withStatus($statusCode);
    }
}
