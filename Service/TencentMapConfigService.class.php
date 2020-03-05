<?php
/**
 * User: jayinton
 * Date: 2019/12/20
 * Time: 17:57
 */

namespace Lbs\Service;


use System\Service\BaseService;

class TencentMapConfigService extends BaseService
{
    /**
     * 根据ID获取
     *
     * @param $id
     * @return array
     */
    static function getById($id) {
        return self::find('Lbs/TencentMapConfig', ['id' => $id]);
    }


    /**
     * 获取列表
     *
     * @param array  $where
     * @param string $order
     * @param int    $page
     * @param int    $limit
     * @param bool   $isRelation
     * @return array
     */
    static function getList($where = [], $order = '', $page = 1, $limit = 20, $isRelation = false) {
        return self::select('Lbs/TencentMapConfig', $where, $order, $page, $limit, $isRelation);
    }

    /**
     * 添加
     *
     * @param array $data
     * @return array
     */
    static function createItem($data = []) {
        return self::create('Lbs/TencentMapConfig', $data);
    }

    /**
     * 添加配置项
     *
     * @param array $data
     * @return array
     */
    static function createCongfig($data = []) {
        return self::create('Lbs/TencentMapOtherConfig', $data);
    }

    /**
     * 更新
     *
     * @param       $id
     * @param array $data
     * @return array
     */
    static function updateItem($id, $data = []) {
        return self::update('Lbs/TencentMapConfig', ['id' => $id], $data);
    }

    /**
     * 删除
     *
     * @param $id
     * @return array
     */
    static function deleteItem($id) {
        return self::delete('Lbs/TencentMapConfig', ['id' => $id]);
    }


    /**
     * 删除配置项
     *
     * @param $id
     * @return array
     */
    static function deleteConfig($id) {
        return self::delete('Lbs/TencentMapOtherConfig', ['id' => $id]);
    }



    /**
     * 获取配置信息
     * @param $key
     */
    static function getConfigByKey($key){
        $where = null;
        if($key) $where['key'] = $key;
        return self::select('Lbs/TencentMapOtherConfig', $where);
    }

    /**
     * 更新配置信息
     * @param  $key
     * @param array $data
     * @return array
     */
    static function updateConfigByKey($key, $value ,$data = []) {
        return self::update('Lbs/TencentMapOtherConfig', [$key => $value], $data);
    }

    /**
     * 删除地址信息
     * @param $id
     * @return array
     */
    static function deleteAddress($id) {
        return self::delete('Lbs/TencentMapAddress', ['id' => $id]);
    }

}