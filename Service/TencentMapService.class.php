<?php
/**
 * User: jayinton
 * Date: 2019/12/22
 * Time: 01:38
 */

namespace Lbs\Service;

use GuzzleHttp\Client;
use System\Service\BaseService;
use Lbs\Service\TencentMapConfigService;

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
     * 通过地址获取坐标
     * @param string $address 地址（注：地址中请包含城市名称，否则会影响解析效果）
     * @param string $region 指定地址所属城市，可以不填
     * @return array 返回示例：
     * {
        "status": true,
        "code": 200,
        "data": {
            "return_status": 0,             // 状态码，0为正常,
                                            310请求参数信息有误，
                                            311Key格式错误,
                                            306请求有护持信息请检查字符串,
                                            110请求来源未被授权
            "return_msg":'query ok',        //状态说明
            "lat": 23.08331,                //纬度
            "lng": 113.3172,                //经度
            "address": "新港东路1088号",    //地址
            "formatted_addresses":"广州大道南海珠区政府(敦丰路北)"   //经过腾讯地图优化过的描述方式，更具人性化特点,有时为空
            "ad_info": "中国,广东省,广州市,海珠区"                 //地址部件
        },
        "msg": "",
        "url": "",
        "state": "success"
        }
     *
     */
    function geocoder_address($address, $region = '')
    {
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/?address=' . $address;
        if (!empty($region)) {
            $url .= '&region=' . $region;
        }
        $url .= '&key=' . $this->getKey()['data'];

        //检查数据库是否存在
        $checkData = $this->check_address_sql('Byaddress',$address);
        if($checkData){
            return self::createReturn(true, $checkData);
        }

        //发出请求
        $http = $this->_getHttpClient();
        $reponse = $http->get($url, []);
        $body = (string)$reponse->getBody();
        $body = json_decode($body, true);

        $returnData['return_status'] = $body['status'];
        $returnData['return_msg'] = $body['message'];
        $returnData['lat'] = $body['result']['location']['lat'];
        $returnData['lng'] = $body['result']['location']['lng'];
        $returnData['address'] = $address;
        $returnData['ad_info'] = $body['result']['address_components'];
        $returnData['ad_info'] = $returnData['ad_info']['province'].','.$returnData['ad_info']['city'].','.$returnData['ad_info']['district'].','.$returnData['ad_info']['street'].','.$returnData['ad_info']['street_number'];

        //记录在库
        if($body['status'] == 0 && $body['result']['title'] != ''){
            $dbData = $returnData;
            $dbData['address'] = $address;
            $dbData['formatted_addresses'] = $returnData['title'];
            $dbData['uid'] = $body['result']['ad_info']['adcode'];
            $dbData['create_time'] = date('Y-m-d H:i:s',time());
            $dbData['type'] = 'getAddress';
            $dbres = D('lbs_address_info')->add($dbData);
        }

        return self::createReturn(true, $returnData);
    }

    /**
     * 通过坐标获取地址
     * @param string $location 坐标值 例如23.08331,113.3172
     * @return array 返回示例：
     * {
            "status": true,
            "code": 200,
            "data": {
                "return_status": 0,             // 状态码，0为正常,
                                                310请求参数信息有误，
                                                311Key格式错误,
                                                306请求有护持信息请检查字符串,
                                                110请求来源未被授权
                "return_msg":'query ok',        //状态说明
                "lat": 23.08331,                //纬度
                "lng": 113.3172,                //经度
                "address": "新港东路1088号",    //地址
                "formatted_addresses":"广州大道南海珠区政府(敦丰路北)"   //经过腾讯地图优化过的描述方式，更具人性化特点,有时为空
                "ad_info": "中国,广东省,广州市,海珠区"                 //地址部件
            },
            "msg": "",
            "url": "",
            "state": "success"
        }
     *
     */
    function geocoder_location($location)
    {
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/?location='.$location;
        $url .= '&key=' . $this->getKey()['data'];

        //检查数据库是否存在
        $checkData = $this->check_address_sql('Bylocation',$location);

        if($checkData){
            return self::createReturn(true, $checkData);
        }

        //发出请求
        $http = $this->_getHttpClient();
        $reponse = $http->get($url, []);
        $body = (string)$reponse->getBody();
        $body = json_decode($body, true);

        //构造返回数组
        $returnData['return_status'] = $body['status']; //状态码
        $returnData['return_msg'] = $body['message'];  //状态说明
        $returnData['uid'] = $body['result']['address_reference']['famous_area']['id']; //唯一id
        $returnData['address'] = $body['result']['address'];  //地址
        $returnData['formatted_addresses'] = $body['result']['formatted_addresses']["recommend"];  //腾讯人性化标识
        $returnData['lat'] = $body['result']['location']['lat'];  //纬度
        $returnData['lng'] = $body['result']['location']['lng'];  //经度
        $returnData['ad_info'] = $body['result']['ad_info']['name'];   //区域位置信息

        //记录在库
        if($body['status'] == 0 && $body['result']['address'] != ''){
            $dbData = $returnData;
            $dbData['create_time'] = date('Y-m-d H:i:s',time());
            $dbData['type'] = 'getlocation';
            $dbres = D('lbs_address_info')->add($dbData);
        }
        return self::createReturn(true, $returnData);
    }

    /**
     *  查询数据库是否存在
     *  @param string $type 类型   Byaddress 地址解析   Bylocation 坐标逆解析
     *  @param string $data 值   类型与值对应， 例如  ( Bylocation , '23.10639,113.26897')
     *  @return array 返回示例：
     */
    function check_address_sql($type,$data){
        //两种情况  1同一个地址有多个坐标  纬度相同经度不同  进行更新操作
        //          2直接插入
        $db = D('lbs_address_info');
        //通过地址查询
        if($type == "Byaddress")
        {
            $findres = $db
                ->where(['address'=>$data])
                ->field('id,lat,lng,address,formatted_addresses,ad_info')->find();

            if($findres){
                if($this->check_address_time($findres))
                {
                    $findres['return_status'] = 0; //状态码
                    $findres['return_msg'] = 'query ok';  //状态说明
                    return $findres;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        //通过经纬度查询
        if($type == "Bylocation"){
            $location = explode(',',$data);
            $findres = $db
                ->where(['lat'=>$location[0],'lng'=>$location[1]])
                ->field('id,lat,lng,address,formatted_addresses,ad_info')->find();
            if($findres){
                if($this->check_address_time($findres))
                {
                    $findres['return_status'] = 0; //状态码
                    $findres['return_msg'] = 'query ok';  //状态说明
                    return $findres;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    /**
     * 检查是否过期
     * @param  array $findres  查询到的地址信息数组
     * @return mixed 返回true / false
     */
    function check_address_time($findres){
        $findtime = strtotime($findres['create_time']);
        $nowtime = time();
        $checktime = $nowtime-$findtime;
        //获取配置信息
        $TimeConfig = TencentMapConfigService::getConfigByKey('time');
        $endTime = $TimeConfig['data']['items'][0]['value'] * 24 * 3600;
        if($checktime < ($nowtime+$endTime)){
            return true;
        }else{
            //删除地址记录
            TencentMapConfigService::deleteAddress($findres['id']);
            return false;
        }
    }
}