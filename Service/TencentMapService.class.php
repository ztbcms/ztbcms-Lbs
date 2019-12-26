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
            "status": 0,
            "message": "query ok",
            "result": {
                "title": "新港东路1088号",
                "location": {
                    "lng": 113.37226,
                    "lat": 23.09565
                },
                "ad_info": {
                "adcode": "440105"
                },
                "address_components": {
                    "province": "广东省",
                    "city": "广州市",
                    "district": "海珠区",
                    "street": "新港东路",
                    "street_number": "1088"
                },
                "similarity": 0.8,
                "deviation": 1000,
                "reliability": 7,
                "level": 9
            }
        },
        "msg": "",
        "url": "",
        "state": "success"
        }
     */
    function geocoder_address($address, $region = '')
    {
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/?address=' . $address;
        if (!empty($region)) {
            $url .= '&region=' . $region;
        }
        $url .= '&key=' . $this->getKey()['data'];

        $http = $this->_getHttpClient();

        $reponse = $http->get($url, []);

        $body = (string)$reponse->getBody();
        $body = json_decode($body, true);


        return self::createReturn(true, $body);
    }
}