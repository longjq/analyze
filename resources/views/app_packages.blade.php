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
                    <div class="form-group">
                        <label style="display: none;">
                            有MD5 <input type="checkbox" name="is_md5" value="1">
                        </label>
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
                    <th style="width: 130px;" class="text-center">用户数</th>
                </tr>
                </thead>
                <tbody>

                    @foreach($packages as $package)
                        <tr>
                            <td>{{ isset($package->package_unique) ? $package->package_unique : $package[1] }}</td>
                            <td>
                                {{--@if(isset($package->user_count_group))--}}
                                    {{--{{ isset($package->package->package_title) ? $package->package->package_title : '' }}--}}

                                {{--@else--}}
                                    {{--{{ isset($package->package_title) ? $package->package_title : $package[0] }}--}}
                                {{--@endif--}}
                                @if( isset($userId) )
                                    {{ isset($package->package_title) ? $package->package_title : $package[0] }}
                                @else
                                    {{ \App\Models\Package::getTitle($package->package_unique)->package_title }}
                                @endif

                            </td>
                            @if( isset($userId) )
                                <td>&nbsp;</td>
                                @else
                                <td class="text-center">
                                    <button class="btn btn-success user_detail">
                                        用户数 <span class="badge">
                                        @if(isset($package->user_count_group))
                                            {{ $package->user_count_group }}
                                            @else
                                            {{ $package->usersPackage()->count() }}
                                            @endif
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