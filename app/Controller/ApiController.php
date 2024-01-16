<?php

namespace App\Controller;

use Closure;
use App\Core\Auth;
use App\Model\Post;
use App\Model\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController
{
    protected $data;
    protected $files;
    protected $resource;
    protected $namespace;
    private $current_call;
    protected $slimRequest;
    protected $slimResponse;
    protected $content_type = 'application/json';
    protected $apiRoute = ROUTES_PATH . DIRECTORY_SEPARATOR . 'api';

    public function __construct(
        private readonly Auth $auth,
        private readonly User $user,
        private readonly Post $post
    )
    {
    }
    
    /**
     * Process API request
     */
    public function process(Request $request, Response $response): Response
    {
        $this->slimRequest = $request;
        $this->slimResponse = $response;
        $this->files = $_FILES;
        $this->data = $this->cleanInputs($request->getParsedBody());
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
        $apiPath = $this->apiRoute . DIRECTORY_SEPARATOR;
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
            return $this->response(['error' => 'method_not_found'], 404);
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
            $error = ['error' => 'method_not_callable', 'method' => $method];
            $this->response($error, 404);
        }
    }
   
    /**
     * Return JSON Response
     */
    public function response(array $payload, int $statusCode = 200)
    {
        $this->slimResponse->getBody()->write(packJson($payload));
        return $this->slimResponse
            ->withHeader('Content-Type', $this->content_type)
            ->withStatus($statusCode);
    }
}