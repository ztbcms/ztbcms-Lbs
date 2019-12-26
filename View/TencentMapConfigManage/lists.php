<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <h3>配置</h3>
            <div class="filter-container">
                <el-button type="primary" @click="toEdit()">添加</el-button>

            </div>
            <el-table
                :data="list"
                border
                fit
                highlight-current-row
                style="width: 100%;"
            >
                <el-table-column label="ID" align="center">
                    <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="key" align="center">
                    <template slot-scope="scope">
                        <span>{{ scope.row.key }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="secret_key" align="center">
                    <template slot-scope="scope">
                        <span>{{ scope.row.secret_key }}</span>
                    </template>
                </el-table-column>

                <el-table-column label="操作" align="center" width="230" class-name="small-padding fixed-width">
                    <template slot-scope="scope">
                        <el-button type="primary" size="mini" @click="toEdit(scope.row)">
                            编辑
                        </el-button>
                        <el-button size="mini" type="danger"
                                   @click="toDelete(scope.row)">
                            删除
                        </el-button>
                    </template>
                </el-table-column>

            </el-table>

            <div class="pagination-container">
                <el-pagination
                    background
                    layout="prev, pager, next, jumper"
                    :total="total"
                    v-show="total>0"
                    :current-page.sync="listQuery.page"
                    :page-size.sync="listQuery.limit"
                    @current-change="getList"
                >
                </el-pagination>
            </div>

        </el-card>
    </div>

    <style>
        .filter-container {
            padding-bottom: 10px;
        }

        .pagination-container {
            padding: 32px 16px;
        }
    </style>

    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    form: {
                        id: '',
                        name: '',
                    },
                    list: [],
                    total: 0,
                    listQuery: {
                        page: 1,
                        limit: 20,
                    },
                },
                watch: {},
                filters: {},
                methods: {
                    search: function () {
                        this.listQuery.page = 1;
                        this.getList();
                    },
                    getList: function () {
                        var that = this;
                        $.ajax({
                            url: "/Lbs/TencentMapConfigManage/getList",
                            type: "post",
                            dataType: "json",
                            data: that.listQuery,
                            success: function(res){
                                if(res.status){
                                    that.list = res.data.items
                                    that.total = res.data.total_items
                                }else{
                                    layer.msg(res.msg)
                                }
                            }
                        });
                    },
                    doDelete: function (id) {
                        var that = this;
                        $.ajax({
                            url: "/Lbs/TencentMapConfigManage/doDelete",
                            type: "post",
                            dataType: "json",
                            data: {id: id},
                            success: function(res){
                                layer.msg(res.msg)
                                if(res.status){
                                    that.getList()
                                }
                            }
                        });
                    },
                    toDelete: function (item) {
                        var that = this;
                        layer.confirm('是否确定删除该项内容吗？', {
                            btn: ['确认', '取消'] //按钮
                        }, function () {
                            that.doDelete(item.id)
                            layer.closeAll();
                        }, function () {
                            layer.closeAll();
                        });
                    },
                    toEdit: function(item = null){
                        var  that = this
                        var url = "/Lbs/TencentMapConfigManage/edit"
                        if(item){
                            url += '?id=' + item.id
                        }
                        layer.open({
                            type: 2,
                            title: '操作',
                            shadeClose: true,
                            area: ['90%', '90%'],
                            content: url,
                            end: function(){
                                that.getList()
                            }
                        });
                    }
                },
                mounted: function () {
                    this.getList();
                },

            })
        })
    </script>
</block>