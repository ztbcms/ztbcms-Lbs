<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;" v-cloak>
        <div id="mapContainer" style="width:100%;height:100%;"></div>
        <el-button @click="submit" type="primary" style="position: absolute;bottom: 20px;right: 20px;">确定</el-button>
    </div>
    <script charset="utf-8" src="//map.qq.com/api/js?v=2.exp&key={$key}"></script>
    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    latLng: null
                },
                watch: {},
                filters: {},
                methods: {
                    initMap: function(){
                        var that = this;
                        layer.load(1, {shade: [0.5,'#000']});
                        var center = new qq.maps.LatLng(39.916527, 116.397128);
                        var map = new qq.maps.Map(document.getElementById('mapContainer'), {
                            center: center,
                            zoom: 13
                        });
                        //获取城市列表接口设置中心点
                        citylocation = new qq.maps.CityService({
                            complete: function (result) {
                                layer.closeAll();
                                map.setCenter(result.detail.latLng);
                                that.latLng = result.detail.latLng;
                                //添加标记
                                marker = new qq.maps.Marker({
                                    position: result.detail.latLng,
                                    map: map
                                });
                                qq.maps.event.addListener(map, 'click', function(e){
                                    console.log(e);
                                    marker.setPosition(e.latLng);

                                    that.latLng = e.latLng;
                                });
                            }
                        });
                        //调用searchLocalCity();方法    根据用户IP查询城市信息。
                        citylocation.searchLocalCity();
                    },
                    submit: function(){
                        var that = this;
                        var event = document.createEvent('CustomEvent');
                        event.initCustomEvent('LBS_LOCATION_PICKER', true, true, {
                            result: that.latLng
                        });
                        window.parent.dispatchEvent(event)
                        that.closePanel();
                    },
                    closePanel: function(){
                        if(parent.window.layer){
                            parent.window.layer.closeAll();
                        }else{
                            window.close();
                        }
                    }
                },
                mounted: function () {
                    this.initMap();
                }
            })
        })
    </script>
</block>
