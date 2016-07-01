@extends('layout.master')

@section('title', '统计分析')


@section('content')
    <div class="row">
        <div class="col-sm-3">
            <h3 class="page-header" style="margin:0 0 20px;">系统总用户数
                <i class="icon icon-user"></i>
            </h3>
            <div class="panel bg-pink-deePink small-box color-withe">
                <div class="panel-body">
                    <div class="col-sm-4">
                        <i class="icon-user icon-4x color-withe"></i>
                    </div>
                    <div class="col-sm-8">
                        <span>
                            总用户数
                        </span>
                        <h3>
                            {{ !empty($caches['total']) ? $caches['total'] : 0 }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="alert alert-info">
                <p>{{ isset($last) ? $last : '' }}</p>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-header" style="margin:0 0 20px;">新增数
                        <i class="icon icon-user"></i>
                        <a href="/dash/new" class="btn btn-default pull-right ">
                            <i class="glyphicon glyphicon-refresh"></i>
                            刷新新增
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <div class="panel bg-red small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            昨日新增
                        </span>
                                <h3>
                                    {{ !empty($caches['last_new_day']) ? $caches['last_new_day'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-red small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            上周新增
                        </span>
                                <h3>
                                    {{ !empty($caches['last_new_week']) ? $caches['last_new_week'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-red small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            上月新增
                        </span>
                                <h3>
                                    {{ !empty($caches['last_new_month']) ? $caches['last_new_month'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel bg-green-seagreen small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            今日新增
                        </span>
                                <h3>
                                    {{ !empty($caches['now_new_day']) ? $caches['now_new_day'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-green-seagreen small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            本周新增
                        </span>
                                <h3>
                                    {{ !empty($caches['now_new_week']) ? $caches['now_new_week'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-green-seagreen small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            本月新增
                        </span>
                                <h3>
                                    {{ !empty($caches['now_new_month']) ? $caches['now_new_month'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-header">活跃数
                        <i class="icon icon-user"></i>
                        <a href="/dash/hot" class="btn btn-default pull-right ">
                            <i class="glyphicon glyphicon-refresh"></i>
                            刷新活跃</a>

                    </h3>
                </div>
                <div class="col-sm-6">
                    <div class="panel bg-yellow small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            昨日活跃
                        </span>
                                <h3>
                                    {{ !empty($caches['last_hot_day']) ? $caches['last_hot_day'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-yellow small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            上周活跃
                        </span>
                                <h3>
                                    {{ !empty($caches['last_hot_week']) ? $caches['last_hot_week'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-yellow small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            上月活跃
                        </span>
                                <h3>
                                    {{ !empty($caches['last_hot_month']) ? $caches['last_hot_month'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel bg-green small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            今日活跃
                        </span>
                                <h3>
                                    {{ !empty($caches['now_hot_day']) ? $caches['now_hot_day'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-green small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            本周活跃
                        </span>
                                <h3>
                                    {{ !empty($caches['now_hot_week']) ? $caches['now_hot_week'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-green small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            本月活跃
                        </span>
                                <h3>
                                    {{ !empty($caches['now_hot_month']) ? $caches['now_hot_month'] : 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-header">留存率
                        <i class="icon icon-user"></i>
                        <a href="/dash/live" class="btn btn-default pull-right ">
                            <i class="glyphicon glyphicon-refresh"></i>
                            刷新留存</a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <div class="panel bg-aqua small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            平均次日留存率
                        </span>
                                <h3>
                                    {{ !empty($caches['last_avg_day']) ? $caches['last_avg_day'] : 0 }} %
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-aqua small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            平均7日留存率
                        </span>
                                <h3>
                                    {{ ! empty($caches['last_avg_seven_day']) ? $caches['last_avg_seven_day'] : 0 }} %
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-aqua small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            平均30日留存率
                        </span>
                                <h3>
                                    {{ ! empty($caches['last_avg_thirty_day']) ? $caches['last_avg_thirty_day'] : 0 }} %
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel bg-green-mediumSeagreen small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                           @if(!empty($caches['now_avg_day_date']))
                                <code>{{ $caches['now_avg_day_date'] }}</code>
                            @endif
                            次日存活率
                        </span>
                                <h3>
                                    {{ ! empty($caches['now_avg_day']) ? $caches['now_avg_day'] : 0 }} %
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-green-mediumSeagreen small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            @if(!empty($caches['now_avg_week_date']))
                                <code>{{ $caches['now_avg_week_date'] }}</code>
                            @endif
                            7日存活率
                        </span>
                                <h3>
                                    {{ ! empty($caches['now_avg_week']) ? $caches['now_avg_week'] : 0 }} %
                                    <small></small>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-green-mediumSeagreen small-box color-withe">
                        <div class="panel-body">
                            <div class="col-sm-4">
                                <i class="icon-user icon-4x color-withe"></i>
                            </div>
                            <div class="col-sm-8">
                        <span>
                            @if(!empty($caches['now_avg_month_date']))
                                <code>{{ $caches['now_avg_month_date'] }}</code>
                            @endif
                            30日存活率
                        </span>
                                <h3>
                                    {{ !empty($caches['now_avg_month']) ? $caches['now_avg_month'] : 0 }} %
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection