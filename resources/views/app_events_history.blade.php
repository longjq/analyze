@extends('layout.master')

@section('title', '应用历史排行查询 - 数据统计')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-header" style="margin:0 0 20px;">应用历史排行查询
                <small>根据包名和事件类型查询历史排行</small>
            </h3>
        </div>
    </div>
    <div class="row">
            <form id="app" class="form-inline text-center" method="post">
                {{ csrf_field() }}

                <div class="form-group {{ $errors->has('start_date') ? 'has-error' : ''}}">
                    <label class="text-right" style="font-weight: normal;" for="start_date">开始日期：</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="start_date" value="{{ isset($start_date) ? $start_date : '' }}" id="start_date">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                    </div>
                </div>
                &nbsp;&nbsp;&nbsp;
                <div class="form-group {{ $errors->has('end_date') ? 'has-error' : ''}}">
                    <label class="text-right" style="font-weight: normal;"  for="end_date">结束日期：</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="end_date" value="{{ isset($end_date) ? $end_date : '' }}" id="end_date">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                    </div>
                </div>
                &nbsp;&nbsp;&nbsp;
                <div class="form-group {{ $errors->has('e') ? 'has-error' : ''}}">
                    @if($errors->count() > 0)
                        <label class="radio-inline">
                            <input type="radio" {{ old('e') == 'inst' ? 'checked' : '' }} name="e"
                                   value="inst"> 安装
                        </label>
                        <label class="radio-inline">
                            <input type="radio" {{ old('e') == 'uninst' ? 'checked' : '' }} name="e"
                                   value="uninst"> 卸载
                        </label>
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
                &nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary">查询</button>
            </form>
        </div>
    <br>
        <div class="col-sm-12">
        @if(isset($lists))
                <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th style="width: 200px;">包名</th>
                    <th>应用名</th>
                    <th>版本</th>
                    <th>{{ $e == 'inst' ? '安装': '卸载' }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td>{{ $item->package }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->version }}</td>
                        <td>{{ $item->event_count }}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
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