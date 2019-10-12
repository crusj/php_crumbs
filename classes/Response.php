<?php
/**
 * author crusj
 * date   2019/10/12 4:48 下午
 */


namespace crusj\php_crumbs\classes;


abstract class Response
{
    /**
     * @var array
     */
    protected $data;

    public function __construct($data = [])
    {
        if (is_array($data)) {
            $this->data = $data;
        }else{
            $this->data = [
                'data' => $data,
            ];
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
