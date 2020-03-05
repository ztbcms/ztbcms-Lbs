<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <h3></h3>
            <div class="filter-container">
                <el-tabs v-model="activeName" @tab-click="clickTabs">
                    <el-tab-pane label="基本信息" name="1">
                        <el-form :model="form">
                            <el-form-item label="配置项名称" label-width="120px" >
                                <el-input v-model="form.name" style="width: 400px" placeholder="请输入内容"></el-input>
                            </el-form-item>
                            <el-form-item label="配置项Key" label-width="120px" >
                                <el-input v-model="form.key" style="width: 400px" placeholder="请输入内容"></el-input>
                            </el-form-item>
                            <el-form-item label="值" label-width="120px" >
                                <el-input v-model="form.value" style="width: 400px" placeholder="请输入内容"></el-input>
                            </el-form-item>

                            <el-form-item>
                                <el-button type="primary" @click="doEdit">保存</el-button>
                            </el-form-item>
                        </el-form>

                    </el-tab-pane>

                </el-tabs>

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
                    activeName: "1",
                    form: {
                        id: '{:I("get.id")}',
                        key: '{:I("get.key")}',
                        name: '{:I("get.name")}',
                    },
                },
                watch: {},
                filters: {},
                methods: {
                    clickTabs: function(tab){},
                    doEdit: function () {
                        var that = this
                        $.ajax({
                            url: "/Lbs/TencentMapConfigManage/doEditKeyconfig",
                            type: "post",
                            dataType: "json",
                            data: that.form,
                            success: function(res){
                                if(res.status){
                                    layer.msg('操作成功');
                                    if (window !== window.parent) {
                                        setTimeout(function () {
                                            window.parent.layer.closeAll();
                                        }, 1000);
                                    }
                                }else{
                                    layer.msg(res.msg)
                                }
                            }
                        });
                    },
                    getDetail: function (key) {
                        var that = this
                        $.ajax({
                            url: "/Lbs/TencentMapConfigManage/getKeyconfig?key="+key,
                            type: "get",
                            dataType: "json",
                            success: function(res){
                                console.log(res)
                                if(res.status){
                                    that.form = res.data.items[0]
                                }else{
                                    layer.msg(res.msg)
                                }
                            }
                        });
                    },

                },
                mounted: function () {
                    if(this.form.key){
                        this.getDetail(this.form.key)
                    }
                },
            })
        })
    </script>
</block>

