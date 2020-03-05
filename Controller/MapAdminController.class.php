<?php
/**
 * User: jayinton
 * Date: 2019/12/26
 * Time: 10:41
 */

namespace Lbs\Controller;


use Common\Controller\AdminBase;
use Lbs\Service\TencentMapService;

class MapAdminController extends AdminBase
{
    /**
     * 实例页面
     */
    function demo()
    {
        $this->display();
    }


    /**
     * 选点返回数据
     */
    function select_address_tencent()
    {
        $service = new TencentMapService();
        $key = $service->getKey()['data'];
        $this->assign('key', $key);
        $this->display();
    }

    /**
     * 通过地址解析坐标
     * @param string $address 地址
     * @param string $region  指定地址所属城市
     * @return array
     *
     */
    function geocoder_address_tencent(){
        $service = new TencentMapService();
        $address = I('address');
        $region = I('region');
        $res = $service->geocoder_address($address, $region);
        $this->ajaxReturn($res);
    }

    /**
     * 通过坐标逆解析地址
     * @param string $location 坐标
     * @return array
     */
    function geocoder_location_tencent(){
        $service = new TencentMapService();
        $location = I('location');
        $res = $service->geocoder_location($location);
        $this->ajaxReturn($res);
    }

}