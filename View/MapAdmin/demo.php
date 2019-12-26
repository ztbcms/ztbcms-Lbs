<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <h3>演示</h3>
            <p>拾取标：<a href="http://www.gpsspg.com/maps.htm">http://www.gpsspg.com/maps.htm</a></p>
            <h3></h3>
            <div class="filter-container">
                <el-form >
                    <el-form-item required>
                        <template v-if="location.cityname">
                            <p style="margin: 0;">城市：{{ location.cityname }}</p>
                            <p style="margin: 0;">地点名称：{{ location.poiname }}</p>
                            <p style="margin: 0;">位置：{{ location.poiaddress }}</p>
                            <p style="margin: 0;">经纬度：{{ location.latlng.lng }},{{ location.latlng.lat }}</p>
                        </template>
                        <el-button type="primary" @click="toLocationPicker">选择地址</el-button>
                    </el-form-item>
                </el-form>
            </div>

            <h3>地址解析</h3>
            <div class="filter-container">
                <el-form >
                    <el-form-item required>
                        <el-form-item label="地址（注：地址中请包含城市名称，否则会影响解析效果）">
                            <el-input v-model="form1.address"></el-input>
                        </el-form-item>
                        <el-form-item label="指定地址所属城市，可以不填">
                            <el-input v-model="form1.region"></el-input>
                        </el-form-item>
                        <el-button style="margin-top: 8px" type="primary" @click="to_geocoder_address_tencent">地址解析</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-card>
    </div>

    <style>
        .filter-container {
            padding-bottom: 10px;
        }

    </style>
    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    location: {
                        cityname: '',
                        poiname: '',
                        poiaddress: '',
                        latlng: {
                            lat: '',
                            lng: '',
                        },
                    },
                    form1: {
                        address: '',
                        region: '',
                    }
                },
                watch: {},
                filters: {},
                methods: {
                    toLocationPicker: function () {
                        layer.open({
                            type: 2,
                            title: '操作',
                            content: "/Lbs/MapAdmin/select_address_tencent",
                            area: ['50%', '80%'],
                        })
                    },
                    onReceiveLocation: function(event){
                        var that = this;
                        console.log(event)
                        var res = event.detail.result
                        console.log(res)
                        if (res) {
                            console.log(res)
                            this.location = res
                        }
                    },
                    to_geocoder_address_tencent: function(){
                        var url = '/Lbs/MapAdmin/geocoder_address_tencent?address=' + this.form1.address + '&region=' + this.form1.region
                        window.open(url)
                    }
                },
                mounted: function () {
                    window.addEventListener('LBS_LOCATION_PICKER', this.onReceiveLocation.bind(this));
                },
            })
        })
    </script>
</block>
