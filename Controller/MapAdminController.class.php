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

    function select_address_tencent()
    {
        $service = new TencentMapService();
        $key = $service->getKey()['data'];
        $this->assign('key', $key);
        $this->display();
    }

    function geocoder_address_tencent(){
        $service = new TencentMapService();
        $address = I('address');
        $region = I('address');
        $res = $service->geocoder_address($address, $region);
        $this->ajaxReturn($res);

    }

}