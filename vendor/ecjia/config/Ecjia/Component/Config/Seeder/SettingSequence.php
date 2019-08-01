<?php


namespace Ecjia\Component\Config\Seeder;

use Ecjia\Component\Config\Models\ConfigModel;
use Royalcms\Component\Database\Eloquent\Builder;

/**
 * 分组排序
 * Class SettingSequence
 * @package Ecjia\Component\Config\Seeder
 */
class SettingSequence
{

    /**
     * shop_config字段排序
     */
    public function seeder()
    {
        /**
         * @var $model ConfigModel | Builder
         */
        $model = new ConfigModel();

        $data = $model->where('id', '>', 100)->get();

        $data->map(function ($item) use ($model) {

            $id = $item['id'] + 30000;
            $model->where('code', $item['code'])->update(['id' => $id]);
        });

        $data = $model->where('parent_id', 0)->get();

        $data->map(function ($item) {
            $this->updateGroupId($item['id']);
        });

    }

    protected function updateGroupId($group_id)
    {
        /**
         * @var $model ConfigModel | Builder
         */
        $model = new ConfigModel();

        $data = $model->where('id', '>', 100)->where('parent_id', $group_id)->get();

        $data->map(function ($item, $key) use ($model) {

            $id = $key + 1;

            $id = $item['parent_id'] * 100 + $id;

            $model->where('code', $item['code'])->update(['id' => $id]);
        });
    }

}