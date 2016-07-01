@extends('layout.master')

@section('title', '应用用户统计 - 数据统计')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-header" style="margin:0 0 20px;">应用用户统计
                <small>导出应用用户数据</small>
            </h3>
        </div>
    </div>

    @if($errors->count() > 0)
        <div class="alert alert-danger">
            <p>必须输入包名</p>
        </div>
    @endif
    @if(isset($ids))
        <div class="alert alert-success text-center">
            @if(count($ids) > 1)
                当前用户数：{{ count($ids) -1 }}
                &nbsp;&nbsp;&nbsp;
                <a href="/app/download_users?path={{ $path }}" class="btn btn-primary btn-lg">下载数据</a>
            @else
                当前用户数：<code>0</code>，没有查询到相关用户
            @endif
        </div>
    @endif
    <div class="row">
        <form class="form-horizontal" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-4">
                    <input type="text" placeholder="包名(package)" class="form-control"
                           value="@if(isset($where[0]['col'])){{ $where[0]['col'] }}@endif" name="where[0][col]"
                           id="package">
                </div>
                <div class="col-sm-1">
                    <a id="addRow" class="btn btn-sm btn-primary">+</a>
                </div>
            </div>
            <div id="mark">
                @if(isset($where))
                    @for($i=1;$i<count($where);$i++)
                        <div class="form-group">
                            <div class="col-sm-4 text-right">
                                <label>
                                    <input type="radio" value="and" @if(isset($where[$i]['condition']) && $where[$i]['condition'] == 'and') checked @endif name="where[{{ $i }}][condition]"> 与
                                </label>
                                <label>
                                    <input type="radio" value="or" @if(isset($where[$i]['condition']) && $where[$i]['condition'] == 'or') checked @endif  name="where[{{ $i }}][condition]"> 或 </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" placeholder="包名(package)" class="form-control"
                                       value="{{ $where[$i]['col'] }}" name="where[{{ $i}}][col]">
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
            <div class="col-sm-offset-4">
                <button type="submit" class="btn btn-success btn-lg">查询</button>
            </div>

        </form>
    </div>

@endsection

@section('js')
    <script type="text/html" id="data_template">
        <div class="form-group">
            <div class="col-sm-4 text-right">
                <label>
                    <input type="radio" value="and" name="where[#index#][condition]"> 与
                </label>
                <label>
                    <input type="radio" value="or" name="where[#index#][condition]"> 或 </label>
            </div>
            <div class="col-sm-4">
                <input type="text" placeholder="包名(package)" class="form-control"
                       value="" name="where[#index#][col]">
            </div>
        </div>
    </script>
    <script>
        function init() {
            var data = [];
            for (i = 0; i < data.length; i++) {
                if (data[i].condition) {
                    $template = $('#data_template');
                    var tpl = $template.html();
                }
            }
        }
        $(function () {


            $('#addRow').on('click', function () {
                $template = $('#data_template');
                var tpl = $template.html();
                var index = $(this).attr('index') ? $(this).attr('index') : 0;
                if (index == 0) index++;
                tpl = tpl.replace(/#index#/gi, index);
                $('#mark').append(tpl);
                $(this).attr('index', ++index);
            })
        })
    </script>
@endsection