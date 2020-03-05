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
     *  接收地址，或者坐标，返回数据
     */
    function geocoder_address_tencent(){
        $service = new TencentMapService();
        $address = I('address');
        $region = I('address');
        $res = $service->geocoder_address($address, $region);
        $this->ajaxReturn($res);
    }

    /**
     *  接收坐标，返回地址数据
     */
    function geocoder_location_tencent(){
        $service = new TencentMapService();
        $location = I('location');
        $res = $service->geocoder_location($location);
        $this->ajaxReturn($res);
    }

}