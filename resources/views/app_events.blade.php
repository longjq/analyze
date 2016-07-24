@extends('layout.master')

@section('title', '应用查询 - 数据统计')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-header" style="margin:0 0 20px;">应用查询
                <small>根据包名和事件类型查询用户总数及导出用户ID列表</small>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">查询结果</h3>
                </div>
                <div class="panel-body">
                    @if( isset($count) && $count > 0 )
                        <p>查询得到数据共：{{ $count }}</p>
                        <p>xls文件已生成：<code>{{ $path }}</code></p>
                    @elseif(isset($path) && $path == 'no')
                        <p>没有查询得到任何数据</p>
                    @endif


                    @if( isset($count) && $count > 0 )
                        <a href="/app/download_events?path={{ $path }}" class="btn btn-primary btn-lg">下载数据</a>
                    @else
                        <p>请输入查询条件</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">应用查询</h3>
                </div>
                <form class="form-horizontal" method="post" action="{{ url('/app/events') }}">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('p') ? 'has-error' : ''}}">
                            <label class="col-sm-3 text-right" for="package">包名：</label>
                            <div class="input-group col-sm-8">
                                @if($errors->count() > 0)
                                    <input type="text" class="form-control" value="{{ old('p') }}" name="p" id="package"
                                           placeholder="package">
                                    {!! $errors->first('p', '<p class="help-block">:message</p>') !!}
                                @else
                                    <input type="text" class="form-control" value="{{ isset($p) ? $p : '' }}" name="p"
                                           id="package"
                                           placeholder="package">
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('e') ? 'has-error' : ''}}">
                            <label class="col-sm-3 text-right">事件：</label>
                            <div class="input-group col-sm-8">
                                @if($errors->count() > 0)
                                    <label class="radio-inline">
                                        <input type="radio" {{ old('e') == 'inst' ? 'checked' : '' }} name="e"
                                               value="inst"> 安装
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" {{ old('e') == 'uninst' ? 'checked' : '' }} name="e"
                                               value="uninst"> 卸载
                                    </label>
                                    {!! $errors->first('e', '<p class="help-block">:message</p>') !!}
                                @else
                                    <label class="radio-inline">
                                        <input type="radio" {{ isset($e) && $e == 'inst' ? 'checked' : '' }} name="e"
                                               value="inst"> 安装
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" {{ isset($e) && $e == 'uninst' ? 'checked' : '' }} name="e"
                                               value="uninst"> 卸载
                                    </label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('start_date') ? 'has-error' : ''}}">
                            <label class="col-sm-3 text-right" for="start_date">开始日期：</label>
                            <div class="input-group col-sm-8">
                                <input type="text" class="form-control" name="start_date" value="{{ isset($start_date) ? $start_date : '' }}" id="start_date">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                            </div>

                        </div>
                        <div class="form-group {{ $errors->has('end_date') ? 'has-error' : ''}}">
                            <label class="col-sm-3 text-right" for="end_date">结束日期：</label>
                            <div class="input-group  col-sm-8">
                                <input type="text" class="form-control" name="end_date" value="{{ isset($end_date) ? $end_date : '' }}" id="end_date">
                                <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <button type="submit" class="btn btn-success btn-lg">查询</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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