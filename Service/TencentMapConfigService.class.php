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
}