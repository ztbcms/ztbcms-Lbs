<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;" v-cloak>
        <iframe v-if="url" width="100%" height="100%" frameborder=0
                :src="url">
        </iframe>
    </div>

    <style>

    </style>
    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    //参数请参考：https://lbs.qq.com/tool/component-picker.html ,这里只使用了常用的
                    type: '1',
                    search: '1',
                    policy: '1',
                    key: "{$key}",
                    referer: 'myapp',
                    url: '',

                },
                watch: {},
                filters: {},
                methods: {
                    onReceiveMapMessage: function(event){
                        // 接收位置信息，用户选择确认位置点后选点组件会触发该事件，回传用户的位置信息
                        //格式：{module: "locationPicker", latlng: {lat: 23.08249,lng: 113.31701}, poiaddress: "广东省广州市海珠区广州大道南1023", poiname: "名粤小区", cityname: "广州市"}
                        var loc = event.data;

                        //防止其他应用也会向该页面post信息，需判断module是否为'locationPicker'
                        if (loc && loc.module == 'locationPicker') {
                            var event = document.createEvent('CustomEvent');
                            event.initCustomEvent('LBS_LOCATION_PICKER', true, true, {
                                result: loc
                            });
                            window.parent.dispatchEvent(event)
                            this.closePanel();
                        }
                    },
                    closePanel: function(){
                        if(parent.window.layer){
                            parent.window.layer.closeAll();
                        }else{
                            window.close();
                        }
                    },
                },
                mounted: function () {
                    //注册回掉
                    window.addEventListener('message', this.onReceiveMapMessage.bind(this), false)


                    var url = 'https://apis.map.qq.com/tools/locpicker?'
                    url += 'type=' + this.type
                    url += '&search=' + this.search
                    url += '&policy=' + this.policy
                    url += '&key=' + this.key
                    url += '&referer=' + this.referer
                    this.url = url


                },
            })
        })
    </script>
</block>
