<?php


namespace Ecjia\Component\ApiServer\Route;


use Royalcms\Component\Support\Collection;

class ApiRouteManager
{
    /**
     * @var Collection
     */
    protected $apis;

    public function __construct($apis = null)
    {
        if (is_null($apis)) {
            $this->apis = collect(config('api'));
        }
        else {
            $this->apis = collect($apis);
        }

        $this->parseApiList();
    }

    /**
     * 解析API列表
     */
    protected function parseApiList()
    {
        $this->apis = $this->apis->map(function ($item, $api) {
            return new ApiParseRoute($api, $item);
        });
    }

    /**
     * 获取API列表
     * @return array
     */
    public function getApiList()
    {
        return $this->apis->all();
    }

    /**
     * 获取分组API列表
     * @return array
     */
    public function getApiListGroupBy()
    {
        $apis = $this->apis->groupBy(function ($item) {
            return $item->getApp();
        })->toArray();

        return $apis;
    }

}