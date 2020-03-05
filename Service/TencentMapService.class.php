<?php
/**
 * User: jayinton
 * Date: 2019/12/22
 * Time: 01:38
 */

namespace Lbs\Service;

use GuzzleHttp\Client;
use System\Service\BaseService;

class TencentMapService extends BaseService
{
    /**
     * @return Client
     */
    function _getHttpClient()
    {
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => '',
            // You can set any number of default request options.
            'timeout' => 5,
        ]);
    }

    /**
     * 获取key
     * @return mixed
     */
    function getKey()
    {
        $configs = M('lbs_config_tencent')->select();
        $index = rand(0, count($configs) - 1);
        return self::createReturn(true, $configs[$index]['key']);
    }

    /**
     * @param string $address 地址（注：地址中请包含城市名称，否则会影响解析效果）
     * @param string $region 指定地址所属城市，可以不填
     * @return array 返回示例：
     * {
        "status": true,
        "code": 200,
        "data": {
            "return_status": 0,
            "title": "新港东路1088号",
            "location": {
                "lng": 113.37226,
                "lat": 23.09565
            },
            "address_components": {
                "province": "广东省",
                "city": "广州市",
                "district": "海珠区",
                "street": "新港东路",
                "street_number": "1088"
            },
        },
        "msg": "",
        "url": "",
        "state": "success"
        }
     *
     * 此处要修改返回值
     * 获取经纬度
     */
    function geocoder_address($address, $region = '')
    {
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/?address=' . $address;
        if (!empty($region)) {
            $url .= '&region=' . $region;
        }
        $url .= '&key=' . $this->getKey()['data'];

        $db = D('address_info');
        //查询是否存在
        $findres = $db->where(['address'=>$address])->find();
        if($findres){
            //转换时间戳
            $findtime = strtotime($findres['create_time']);
            $nowtime = time();
            $checktime = $nowtime-$findtime;
            //间隔不超过30天
            if($checktime < 2592000){
                return self::createReturn(true, $findres);
            }else{
                //删除记录
                $db->where(['id'=>$findres['id']])->delete();
            }
        }

        //发出请求
        $http = $this->_getHttpClient();
        $reponse = $http->get($url, []);
        $body = (string)$reponse->getBody();
        $body = json_decode($body, true);

        $returnData['return_status'] = $body['status'];
        $returnData['address'] = $body['result']['title'];
        $returnData['lat'] = $body['result']['location']['lat'];
        $returnData['lng'] = $body['result']['location']['lng'];
        $returnData['address_components'] = $body['result']['address_components'];

        //记录在库
        if($body['status'] == 0 && $body['result']['title'] != ''){
            $dbData = $returnData;
            $dbData['address'] = $address;
            $dbData['formatted_addresses'] = $returnData['title'];
            $dbData['uid'] = $body['result']['ad_info']['adcode'];
            $dbData['create_time'] = date('Y-m-d H:i:s',time());
            $dbData['type'] = 'getAddress';
            $dbres = $db->add($dbData);
        }

        return self::createReturn(true, $returnData);
    }

    /**
     * @param $location 坐标值  例如39.1,40
     * @return array  地址数据
     */
    function geocoder_location($location)
    {
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/?location='.$location;
        $url .= '&key=' . $this->getKey()['data'];

        //查询是否存在
        $db = D('address_info');
        //分割逗号
        $location = explode(',',$location);
        $findres = $db->where(['lat'=>$location[0],'lng'=>$location[1],'type'=>'getlocation'])->find();
        if($findres){
            //转换时间戳
            $findtime = strtotime($findres['create_time']);
            $nowtime = time();
            $checktime = $nowtime-$findtime;
            //间隔不超过30天
            if($checktime < 2592000){
                return self::createReturn(true, $findres);
            }else{
                //删除记录
                $db->where(['id'=>$findres['id']])->delete();
            }
        }


        $http = $this->_getHttpClient();
        $reponse = $http->get($url, []);
        $body = (string)$reponse->getBody();
        $body = json_decode($body, true);


        $returnData['lat'] = $body['result']['location']['lat'];
        $returnData['lng'] = $body['result']['location']['lng'];

        $returnData['return_status'] = $body['status'];
        $returnData['address'] = $body['result']['address'];  //地址
        $returnData['formatted_addresses'] = $body['result']['formatted_addresses']["recommend"];  //人性化识别
        $returnData['uid'] = $body['result']['address_reference']['famous_area']['id']; //唯一id
        $returnData['address_components'] = $body['result']['address_components'];   //地点详情
        $returnData['ad_info'] = $body['result']['ad_info'];   //区域位置信息

        //记录在库
        if($body['status'] == 0 && $body['result']['address'] != ''){
            $dbData = $returnData;
            $dbData['ad_info'] = $returnData['ad_info']['name'];
            $dbData['create_time'] = date('Y-m-d H:i:s',time());
            $dbData['type'] = 'getlocation';
            $dbres = $db->add($dbData);
        }
        return self::createReturn(true, $returnData);
    }
}