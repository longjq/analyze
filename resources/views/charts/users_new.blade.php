@extends('layout.master')

@section('title', '新增用户 - 统计分析')


@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-header" style="margin:0 0 20px;">用户新增折线图
                <small>输入选项查询折线图数据</small>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 alert alert-info text-center">
            <form class="form-inline" method="post" action="/charts/users_new">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="year">年</label>
                    <select name="year" class="form-control"  id="year">
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="month">月</label>
                    <select class="form-control" name="month" id="month">
                        <option value="0">请选择</option>
                        <option value="1">一月</option>
                        <option value="2">二月</option>
                        <option value="3">三月</option>
                        <option value="4">四月</option>
                        <option value="5">五月</option>
                        <option value="6">六月</option>
                        <option value="7">七月</option>
                        <option value="8">八月</option>
                        <option value="9">九月</option>
                        <option value="10">十月</option>
                        <option value="11">十一月</option>
                        <option value="12">十二月</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">查询</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div id="chart">

        </div>
    </div>

@endsection

@section('js')
<script>
    var json = {};

    json.title = {
        text: '新增用户曲线图'
    };
    json.subtitle = {
        text: ''
    };
    json.xAxis = {
        categories: [{!! $data['titles'] !!}]
    };
    json.yAxis = {
        title: {
            text: '新增数'
        },
        plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
        }]
    };
    json.tooltip = {
        valueSuffix: '个'
    };
    json.legend = {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
    };
    json.series = [
        {
            name: '新增用户',
            data: [{{ implode(',', $data['datas']) }}]
        }
    ];
    $('#chart').highcharts(json);
</script>
@endsection