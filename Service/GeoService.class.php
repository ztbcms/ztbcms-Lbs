<?php
/**
 * User: jayinton
 * Date: 2020/6/15
 * Time: 17:39
 */

namespace Lbs\Service;


use System\Service\BaseService;

class GeoService extends BaseService
{
    /**
     * @var \Geohash
     */
    private $geohashService;

    public function __construct()
    {
        include_once(APP_PATH.'Lbs/Libs/geohash.class.php');
        $this->geohashService = new \Geohash();
    }


    /**
     * 将给定的位置对象（纬度、经度、位置标识）
     *
     * @param $target_type
     * @param $target_id
     * @param $longitude
     * @param $latitude
     *
     * @return array
     */
    function geoAdd($target_type, $target_id, $latitude, $longitude)
    {
        $record = M('lbs_geohash')->where([
            'target_type' => $target_type,
            'target_id'   => $target_id,
        ])->find();
        $geohash = $this->geoHash($latitude, $longitude);
        $data = [
            'target_type' => $target_type,
            'target_id'   => $target_id,
            'geohash'     => $geohash,
            'latitude'    => $latitude,
            'longitude'   => $longitude,
        ];
        if ($record) {
            M('lbs_geohash')->where(['id' => $record['id']])->save($data);
        } else {
            M('lbs_geohash')->add($data);
        }

        return self::createReturn(true, $data, '操作完成');
    }

    /**
     * 删除位置对象
     *
     * @param $target_type
     * @param $target_id
     *
     * @return array
     */
    function geoRemove($target_type, $target_id){
        $res = M('lbs_geohash')->where(['$target_type' => $target_type, '$target_id' => $target_id])->delete();
        if($res){
            return self::createReturn(true, null, '操作完成');
        }
        return self::createReturn(false, null, '操作失败');
    }

    /**
     * 返回两个给定位置之间的距离
     *
     * @param $latitude_src  string|float 纬度
     * @param $longitude_src string|float 经度
     * @param $latitude_dest string|float 纬度
     * @param $longitude_dest string|float 经度
     *
     * @return float|int
     */
    function geoDistance($latitude_src, $longitude_src, $latitude_dest, $longitude_dest)
    {
        // 将角度转为狐度
        $radLat1 = deg2rad($latitude_src); //deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($latitude_dest);
        $radLng1 = deg2rad($longitude_src);
        $radLng2 = deg2rad($longitude_dest);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $d = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        return floor($d);
    }

    /**
     * 返回一个或多个位置对象的Geohash表示;
     *
     * @param $latitude string|float 纬度
     * @param $longitude  string|float 经度
     * @param $precision int  精度,默认为自动根据纬度的精度来适配
     *
     * @return string
     */
    function geoHash($latitude, $longitude, $precision = 0)
    {
        //得到这点的hash值
        $hash = $this->geohashService->encode($latitude, $longitude);
        if ($precision) {
            return substr($hash, 0, $precision);
        }
        return $hash;
    }

    /**
     * 取出相邻八个区域的geohash
     *
     * @param $latitude
     * @param $longitude
     * @param  int  $precision
     *
     * @return array
     */
    function getNeighbors($latitude, $longitude, $precision = 0)
    {
        //得到这点的hash值
        $hash = $this->geoHash($latitude, $longitude, $precision);
        //取出相邻八个区域
        $neighbors = $this->geohashService->neighbors($hash);
        $return_list = [];
        foreach ($neighbors as $item) {
            $return_list [] = $item;
        }
        return $return_list;
    }

    /**
     * 以给定的经纬度为中心，返回目标集合中与中心的距离不超过给定最大距离的所有位置对象
     *
     * @param $target_type string 类型
     * @param $latitude string|float 纬度
     * @param $longitude string|float 经度
     * @param $radius int 半径 单位：米
     *
     * @return array
     */
    function geoRadius($target_type, $latitude, $longitude, $radius)
    {
        //获取经度
        $precision = $this->getPrecisionByRadius($radius);

        //根据精度计算 geohash
        $geohash = $this->geoHash($latitude, $longitude, $precision);

        // 获取附近的8个点的geohash_list
        $neighbors = $this->getNeighbors($latitude, $longitude, $precision);
        $neighbors [] = $geohash;

        // 获取 geolist 符合条件的数据
        $geohash_like_list = [];
        foreach ($neighbors as $item) {
            $geohash_like_list = $item.'%';
        }
        $where = [
            'target_type' => $target_type,
            'geohash'     => ['LIKE', $geohash_like_list, 'OR']
        ];
        $result_list = M('lbs_geohash')->where($where)->select();

        $return_data = [];
        // 计算所有数据项的距离，清洗数据不符合条件的数据(临近块的数据可能不合适)
        foreach ($result_list as $item) {
            $distance = $this->geoDistance($latitude, $longitude, $item['latitude'], $item['longitude']);
            if ($distance <= $radius) {
                $return_data [] = [
                    'target_id' => $item['target_id'],
                    'distance'  => $distance,
                ];
            }
        }

        //排序,由近到远,同一距离时按 target_id 大的优先
        usort($return_data, function ($a, $b)
        {
            if ($a['distance'] == $b['distance']) {
                return $a['target_id'] < $b['target_id'];
            }
            return $a['distance'] > $b['distance'];
        });
        return $return_data;
    }

    /**
     * 根据给定的要求精度，返回 geohash 长度
     *
     * @param $radius int 半径距离，单位米
     *
     * @return int
     */
    function getPrecisionByRadius($radius)
    {
        switch ($radius) {
            case $radius <= 1:
                return 10;
            case $radius <= 5:
                return 9;
            case $radius <= 20:
                return 8;
            case $radius <= 77:
                return 7;
            case $radius <= 610:
                return 6;
            case $radius <= 2400:
                return 5;
            case $radius <= 20000:
                return 4;
            case $radius <= 78000:
                return 3;
            case $radius <= 630000:
                return 2;
            default:
                return 7;
        }
    }
}