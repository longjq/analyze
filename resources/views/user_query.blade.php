@extends('layout.master')

@section('title', '用户资料导出 - 数据统计')

@section('content')
    <div class="row placeholder">
        <div class="col-md-12">
            <h3 class="page-header" style="margin:0;">用户资料导出
                <small>选择字段查询相关用户资料及导出</small>
            </h3>
        </div>
    </div>
    @if(isset($danger))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <p>请输入查询条件</p>
                </div>
            </div>
        </div>
        @endif
    @if(isset($items))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success text-center">
                @if(isset($path))
                    已查询出数据：<code>{{ count($items) -1 }}</code>
                    &nbsp;&nbsp;&nbsp;
                    <a href="/user/download_query?path={{ $path }}" class="btn btn-primary btn-lg">下载数据</a>
                @else
                    <p>没有相关数据</p>
                @endif
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class=" alert alert-info">
            <form class="form-horizontal" v-on:submit.prevent="onSubmit" id="app" method="post"
                  action="/user/data_export">
                {{ csrf_field() }}
                <div class="form-group text-center">
                    <button type="button" id="delete_row" v-on:click="removeItem" class="btn btn-danger">
                        删除
                    </button>
                    <button type="button" id="add_row" v-on:click="addItem" class="btn btn-info">
                        新增
                    </button>
                    <button type="submit" class="btn btn-success">
                        查询
                    </button>
                </div>
                <template v-for="item in items">
                    <div class="form-group" v-if="$index != 0">
                        <label class="col-md-offset-6 radio-inline">
                            <input required id="r_@{{ $index }}_and" type="radio" name="where[@{{$index}}][condition]"
                                   value="and">
                            和
                        </label>
                        <label class="radio-inline">
                            <input required id="r_@{{ $index }}_or" type="radio" name="where[@{{$index}}][condition]"
                                   value="or">
                            或
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="f_@{{$index}}" class="col-md-1 control-label">
                            字段
                        </label>
                        <div class="col-md-3">
                            <select id="f_@{{$index}}" name="where[@{{$index}}][col]" class="form-control">
                                <option value="@{{field.v}}" v-for="field in fields">@{{field.t}}</option>
                            </select>
                        </div>
                        <label for="o_@{{$index}}" class="col-md-1 control-label">
                            操作符
                        </label>
                        <div class="col-md-3">
                            <select id="o_@{{$index}}" name="where[@{{$index}}][op]" class="form-control field">
                                <option value="@{{op.v}}" v-for="op in ops">@{{op.t}}</option>
                            </select>
                        </div>
                        <label for="v_@{{$index}}" class="col-md-1 control-label">
                            字段(@{{$index}} )
                        </label>
                        <div class="col-md-3">
                            <input required type="text" id="v_@{{$index}}" name="where[@{{$index}}][val]"
                                   class="form-control field" value="">
                        </div>
                    </div>
                </template>

            </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        new Vue({
            el: '#app',
            data: {
                fields: [
                    {v: 'v', t: '版本（v）'},
                    {v: 'lang', t: '语言（lang）'},
                    {v: 'brand', t: '品牌（brand）'},
                    {v: 'device', t: '设备（device）'},
                    {v: 'ov', t: '系统版本（ov）'},
                    {v: 'country', t: '国家（country）'},
                    {v: 'area', t: '区域（area）'},
                    {v: 'region', t: '省份（region）'},
                    {v: 'city', t: '城市（city）'},
                    {v: 'isp', t: '运营商（isp）'}
                ],
                ops: [
                    {v: '=', t: '等于（=）'},
                    {v: '>', t: '大于（>）'},
                    {v: '<', t: '小于（<）'},
                    {v: '>=', t: '大于等于（>=）'},
                    {v: '<=', t: '小于等于（<=）'},
                    {v: '!=', t: '不等于（!=）'}
                ],
                items: []
            },
            methods: {
                addItem: function () {
                    this.items.push({});
                },
                removeItem: function () {
                    if (this.items.length == 1) {
                        return;
                    }
                    this.items.pop();
                },
                onSubmit: function (event) {
//                    $('#app').find(":input[name^=where]").each(function(){
//                        console.log(this.value)
//                    });

                    event.target.submit()
                }
            },
            created: function () {
                var wheres = {!! isset($wheres) ? json_encode($wheres) : 'false' !!}
                if(wheres == false) this.items.push({});
                for (i = 0; i < wheres.length; i++) {
                    this.items.push({});
                    if (i != 0) {
                        if (wheres[i].condition == 'and') {
                            $('#r_' + i + '_and').prop('checked', true);
                        } else if (wheres[i].condition == 'or') {
                            $('#r_' + i + '_or').prop('checked', true);
                        }
                    }

                    $('#f_' + i).val(wheres[i].col);
                    $('#o_' + i).val(wheres[i].op);
                    $('#v_' + i).val(wheres[i].val);
                }


            }
        })
    </script>
@endsection