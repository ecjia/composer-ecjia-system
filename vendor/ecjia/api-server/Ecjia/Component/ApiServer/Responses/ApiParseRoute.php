<?php


namespace Ecjia\Component\ApiServer\Responses;


class ApiParseRoute
{
    /**
     * @var string
     */
    protected $api;

    /**
     * @var string
     */
    protected $handler;

    /**
     * API具体类
     * @var string
     */
    protected $className;

    /**
     * API类所在的路径
     * @var string
     */
    protected $classPath;

    /**
     * API类所在的位置文件名
     * @var string
     */
    protected $fileName;

    /**
     * API所在的APP
     * @var string
     */
    protected $appModule;

    /**
     * ApiParse constructor.
     * @param $api
     * @param $handler
     */
    public function __construct($api, $handler)
    {
        $this->api = $api;
        $this->handler = $handler;

        $this->parseKey();
    }


    protected function parseKey()
    {
        $handler = explode('::', $this->handler);

        $this->appModule = $handler[0];

        $path = dirname($handler[1]);
        $name = basename($handler[1]);

        if ($path == '.') {
            $controller = null;
        } else {
            $controller = $path;
        }

        $this->classPath = $controller;

        $this->className = $name . '_module';

        $this->fileName = $name;
    }

    /**
     * 获取APP
     * @return string
     */
    public function getApp()
    {
        return $this->appModule;
    }

    public function getFullClassName()
    {
        $class = $this->classPath . '/' . $this->className;
        $className = str_replace('/', '_', $class);
        return $className;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getClassPath()
    {
        return $this->classPath;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getFullFileName()
    {
        return $this->fileName . '.class.php';
    }

}