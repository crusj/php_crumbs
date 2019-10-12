<?php
/**
 * author crusj
 * date   2019/10/12 1:49 下午
 */


namespace crusj\php_crumbs\classes;


use \Exception;

class ResponseException extends Exception
{
    /**
     * @var Response
     */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function render()
    {
        return response()->json([
            'code' => $this->response::CODE,
            'data' => $this->response->getData()
        ]);
    }
}
