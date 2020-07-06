<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <h3>地图拾点</h3>
            <div class="filter-container">
                <el-form >
                    <el-form-item required>
                        <template v-if="location.ad_info">
                            <p style="margin: 0;">城市：{{ location.ad_info }}</p>
                            <p style="margin: 0;">地点名称：{{ location.formatted_addresses }}</p>
                            <p style="margin: 0;">位置：{{ location.address }}</p>
                            <p style="margin: 0;">经纬度：{{ location.lng }},{{ location.lat }}</p>
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
                        <el-form-item >
                        <template v-if="location_jx.address">
                            <p style="margin: 0;">地点名称：{{ location_jx.address }}</p>
                            <p style="margin: 0;">经纬度：{{ location_jx.lat }},{{ location_jx.lng }}</p>
                        </template>
                        </el-form-item>
                        <el-button style="margin-top: 8px" type="primary" @click="to_geocoder_address_tencent">地址解析</el-button>
                    </el-form-item>
                </el-form>
            </div>

            <h3>坐标逆解析</h3>
            <div class="filter-container">
                <el-form >
                    <el-form-item required>
                        <el-form-item label="地址（注：地址中请包含城市名称，否则会影响解析效果）">
                            <el-input v-model="form1.location"></el-input>
                        </el-form-item>
                        <template v-if="location_njx.address">
                            <p style="margin: 0;">地点名称：{{ location_njx.address }}</p>
                            <p style="margin: 0;" v-if="location_njx.formatted_addresses">人性化标识：{{ location_njx.formatted_addresses }}</p>
                        </template>

                        <el-button style="margin-top: 8px" type="primary" @click="to_geocoder_location_tencent">坐标解析</el-button>
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
                        ad_info: '',
                        formatted_addresses: '',
                        address: '',
                        lng: '',
                        lat: '',
                    },
                    //地址转坐标
                    location_jx: {
                        address: '',
                        lat: '',
                        lng: '',
                    },
                    //坐标转地址
                    location_njx: {
                        address: '',
                        formatted_addresses: '',
                    },
                    form1: {
                        address: '',
                        region: '',
                        location:''
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
                    //选点获取地址信息
                    onReceiveLocation: function(event){
                        var that = this;
                        console.log(event)
                        var result = event.detail.result
                        var url = '/Lbs/MapAdmin/geocoder_location_tencent?location='+result.lat+','+result.lng
                        $.get(url, function (res) {
                            console.log(res);
                            if(res.status){
                                that.location = res.data
                            }
                        })
                    },
                    //地址转坐标
                    to_geocoder_address_tencent: function(){
                        var url = '/Lbs/MapAdmin/geocoder_address_tencent?address=' + this.form1.address + '&region=' + this.form1.region
                        var that = this
                        $.get(url,function (res) {
                            if(res.status){
                                that.location_jx = res.data
                            }
                        })
                    },

                    //坐标转地址
                    to_geocoder_location_tencent: function(){
                        var url = '/Lbs/MapAdmin/geocoder_location_tencent?location='+ this.form1.location
                        var that = this
                        $.get(url,function (res) {
                            if(res.status){
                                that.location_njx = res.data
                            }
                        })
                    }
                },
                mounted: function () {
                    window.addEventListener('LBS_LOCATION_PICKER', this.onReceiveLocation.bind(this));
                },
            })
        })
    </script>
</block>
