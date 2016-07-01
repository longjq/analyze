@extends('layout.master')

@section('title', '应用用户统计 - 数据统计')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-header" style="margin:0 0 20px;">应用用户查询
                <small>查询app相关的用户数据</small>
            </h3>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <form id="app" class="form-inline text-center" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="user_id">用户ID：</label>
                        <input name="user_id" value="@if(isset($userId)){{$userId}}@endif" type="text"
                               class="form-control" id="package" placeholder="输入用户ID">
                    </div>
                    <div class="form-group">
                        <label for="package">包名：</label>
                        <input name="package" value="@if(isset($package)){{$package}}@endif" type="text"
                               class="form-control" id="package" placeholder="输入包名">
                    </div>
                    <div class="form-group">
                        <label for="name">名称：</label>
                        <input name="name" value="@if(isset($name)){{$name}}@endif" type="text" class="form-control"
                               id="name" placeholder="输入应用名称">
                    </div>
                    <button type="submit" class="btn btn-primary">查询</button>
                </form>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="page text-right">
                @if(method_exists($packages, 'render'))
                    {!! $packages->render() !!}
                @endif
            </div>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th style="width: 200px;">包名</th>
                    <th>名称</th>
                    @if(isset($packages[0]) && isset($packages[0]->user_count))
                    <th style="width: 130px;" class="text-center">用户数</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($packages as $package)
                    <tr>
                        <td>{{ isset($package->package) ? $package->package : $package[1] }}</td>
                        <td>{{ isset($package->name) ? $package->name : $package[0] }}</td>
                        @if(isset($package->user_count))
                        <td class="text-center">
                                <button data-id="{{ $package->id }}" class="btn btn-success user_detail">
                                    用户数 <span class="badge">
                                    {{ $package->user_count }}
                                </span>
                                </button>
                        </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

@section('js')
    {{--<script>--}}
    {{--$(function () {--}}
    {{--$('.user_detail').on('click', function () {--}}
    {{--$btn = $(this);--}}
    {{--if($btn.has('span').length){--}}
    {{--return false;--}}
    {{--}else{--}}
    {{--$.get('/app/package_detail', {pid: $(this).data('id')}, function (d) {--}}
    {{--$btn.append(' <span class="badge">' + d.data + '</span>')--}}
    {{--});--}}
    {{--}--}}
    {{--})--}}
    {{--})--}}
    {{--</script>--}}
@endsection