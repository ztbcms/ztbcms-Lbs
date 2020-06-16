<?php
/**
 * User: jayinton
 * Date: 2020/6/16
 * Time: 11:49
 */

namespace Lbs\Controller;


use Common\Controller\AdminBase;
use Lbs\Service\GeoService;

class GeohashTestController extends AdminBase
{
    function test_GeoService_geoAdd(){
        $service = new GeoService();
        $res = $service->geoAdd('ad', '上海半岛酒店-水疗中心', '31.241501', '121.489010');
        $res = $service->geoAdd('ad', '上海半岛酒店', '31.241250', '121.489158');
        $res = $service->geoAdd('ad', '上海市黄浦区北无锡路53号', '31.238885', '121.480325');
        var_dump($res);
    }

    function test_GeoService_geoHash(){
        $service = new GeoService();
        $lat = '31.241769';
        $lon = '121.489031';
        $res = $service->geoHash($lat, $lon);
        var_dump($res);
    }

    function test_GeoService_getNeighbors(){
        $service = new GeoService();
        $lat = '31.241769';
        $lon = '121.489031';
        $res = $service->getNeighbors($lat, $lon, 7);
        var_dump($res);

        $res = $service->geoHash($lat, $lon, 7);
        var_dump($res);
    }

    function test_GeoService_geoRadius(){
        $service = new GeoService();
        $lat = '31.241769';
        $lon = '121.489031';
        $res = $service->geoRadius('ad', $lat, $lon, 500);
        var_dump($res);


    }
}