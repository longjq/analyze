@extends('layout.master')

@section('title','应用查询 - 安装排行榜')


@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-header" style="margin:0 0 20px;">安装排行榜
                <small>查看安装排行榜</small>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <form id="event_log_form" class="form-inline text-center" method="post">

                    <div class="form-group {{ $errors->has('start_date') ? 'has-error' : ''}}">
                        <label class="text-right" style="font-weight: normal;" for="start_date">开始日期：</label>

                        <div class="input-group">
                            <input type="text" class="form-control" name="start_date"
                                   value="{{ isset($start) ? $start : '' }}" id="start_date">

                            <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                        </div>
                    </div>
                    &nbsp;&nbsp;&nbsp;
                    <div class="form-group {{ $errors->has('end_date') ? 'has-error' : ''}}">
                        <label class="text-right" style="font-weight: normal;" for="end_date">结束日期：</label>

                        <div class="input-group">
                            <input type="text" class="form-control" name="end_date"
                                   value="{{ isset($end) ? $end : '' }}" id="end_date">

                            <div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">查询</button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        统计查询结果
                        @if(isset($today))
                            <span class="text-danger">{{ $today }}</span>
                        @else
                            @if(isset($start) && isset($end))
                                <span class="text-danger">{{ $start }}</span> - <span class="text-danger">{{ $end }}</span>
                            @endif
                        @endif
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered info">
                        <thead>
                        <tr>
                            <th>应用名称
                            </th>
                            <th>包名
                            </th>
                            <th>安装数
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($items) && count($items) > 0)
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->package }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->sum_inst_count }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="3">
                                    <p class="text-danger">暂无数据</p>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                   <div class="text-center">
                       {!! $items->render() !!}
                   </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('body_html')

        <!-- 模态框（Modal） -->
    <div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">
                        <i class="glyphicon glyphicon-alert"></i>
                        警告
                    </h4>
                </div>
                <div class="modal-body">
                    <p class="text-center text-danger">开始时间和结束时间不能为空！</p>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
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

            $('#event_log_form').on('submit',function(){
                if($('#start_date').val().length == 0  || $('#end_date').val().length == 0){
                    $("#alert-modal").modal('show');
                    return false;
                }
            });
        });
    </script>

@endsection