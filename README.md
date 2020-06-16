# 位置服务 LBS

## 腾讯地图

```php
# 通过地址获取坐标
TencentMapService::geocoder_address();
# 通过坐标获取地址
TencentMapService::geocoder_location();
```

## 附近搜索(geohash 算法)基于mysql实现

- geohash php版实现 https://github.com/CloudSide/geohash
- geohash 示例 http://www.geohash.cn/
- 腾讯坐标拾取器 https://lbs.qq.com/tool/getpoint/index.html

使用方法
```php
$service = new GeoService();
// 将给定的位置对象
$service->geoAdd();

// 将给定的位置对象
$service->geoAdd();

// 以给定的经纬度为中心，返回目标集合中与中心的距离不超过给定最大距离的所有位置对象
// 建议对附近搜索的结果进行缓存
$lists = $service->geoRadius();

```

参考阅读
- https://www.jianshu.com/p/4d47a8a69c55
- https://segmentfault.com/a/1190000022734787
- https://segmentfault.com/a/1190000017279755
