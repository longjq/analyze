@extends('layout.master')

@section('title', '平均存活率 - 统计分析')


@section('content')
    <div class="row placeholder">
        <div class="col-md-12">
            <h3 class="page-header" style="margin:0;">平均存活率
                <small>输入指定日期查询平均存活率</small>
            </h3>
        </div>
    </div>
    <div class="row placeholder">
        <div class="col-md-12">
            <form action="/charts/users_list" method="post" class="form-inline text-center">
                {{ csrf_field() }}

                <div class="form-group {{ $errors->has('start_date') ? 'has-error' : ''}}">
                    <label for="start_date">开始日期：</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="start_date"
                               value="{{ isset($startTime) ? date('Y-m-d',$startTime) : '' }}" id="start_date">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('end_date') ? 'has-error' : ''}}">
                    <label for="end_date">日期：</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="end_date"
                               value="{{ isset($endTime) ? date('Y-m-d',$endTime) : '' }}" id="end_date">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">查询</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-center">
            @if( isset($livePer['count']) && $livePer['count'] != 0 )
                <div class="alert alert-info">
                    <code>{{ date('Y-m-d',$startTime) }} 至 {{ date('Y-m-d',$endTime) }}  </code>共新增用户数：<strong><code>{{ $livePer['count'] }}</code></strong></div>
            @elseif( isset($livePer['count']) && $livePer['count'] == 0 )
                <div class="alert alert-warning">
                    当日没有新增用户
                </div>
            @else
                @if( $errors->has('start_date') )
                    {!! $errors->first('start_date', '<div class="alert alert-danger">:message</div >') !!}
                @else
                    <div class="alert alert-info">
                        请选择日期
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="small-box @if(isset($livePer['d1']) && $livePer['d1'] > 0)
                    bg-aqua
                @else
                    bg-gray
                @endif">
                <div class="inner">
                    <h3>
                        {{ isset($livePer['d1']) ? $livePer['d1'] : 0 }} %
                        {{--{{ isset($dayAvg) ? $dayAvg : 0 }} %--}}
                    </h3>
                    <p>
                        次日存活率
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box @if(isset($livePer['d7']) && $livePer['d7'] > 0)
                    bg-green
                @else
                    bg-gray
                @endif">
                <div class="inner">
                    <h3>
                        {{ isset($livePer['d7']) ? $livePer['d7'] : 0 }} %
                    </h3>
                    <p>
                        7日存活率
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box @if(isset($livePer['d15']) && $livePer['d15'] > 0)
                    bg-yellow
                @else
                    bg-gray
                @endif">
                <div class="inner">
                    <h3>
                        {{ isset($livePer['d15']) ? $livePer['d15'] : 0 }} %
                    </h3>
                    <p>
                        15日存活率
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box @if(isset($livePer['d30']) && $livePer['d30'] > 0)
                        bg-red
                    @else
                        bg-gray
                    @endif">
                <div class="inner">
                    <h3>
                        {{ isset($livePer['d30']) ? $livePer['d30'] : 0 }} %
                    </h3>
                    <p>
                        30日存活率
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
    </div>

    {{--<div class="row">--}}
        {{--<div class="col-sm-12">--}}
            {{--<div class="small-box bg-lovely">--}}
                {{--<div class="inner text-center">--}}
                    {{--<h3>--}}
                        {{--{{ isset($dayAvg) ? $dayAvg : 0 }} %--}}
                    {{--</h3>--}}
                    {{--<p>--}}
                        {{--日均存活率--}}
                    {{--</p>--}}
                {{--</div>--}}
                {{--<div class="icon">--}}
                    {{--<i class="ion ion-bag"></i>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection

@section('js')
    <script>
        $(function () {
            $('#start_date').datetimepicker({
                format: 'Y-MM-DD'
            });
            $('#end_date').datetimepicker({
                format: 'Y-MM-DD'
            });
        });
    </script>
@endsection