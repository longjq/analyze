@extends('layout.master')

@section('title', '用户资料 - 数据统计')

@section('content')
    <div class="row placeholder">
        <div class="col-md-12">
            <h3 class="page-header" style="margin:0;">用户资料
                <small>选择字段查询相关字段用户总数</small>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">统计查询结果</h3>
                </div>
                <div class="panel-body">
                    @if(isset($data))
                        <table class="table table-bordered info">
                               <thead>
                               <tr>
                                   <th>
                                       <strong id="query-head"></strong>
                                   </th>
                                   <th>
                                       总计({{ isset($u)  ? $u : '' }})
                                       &nbsp;
                                       {{ isset($dataCount) ? $dataCount : '' }}
                                   </th>
                               </tr>
                               </thead>
                        @foreach($data as $val)
                                <tr>
                                    <td>{{ $val[$u] }}</td>
                                    <td>{{ $val[$u.'_count'] }}</td>
                                </tr>
                        @endforeach
                        </table>
                    @else
                        选择左侧单选按钮进行查询
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">统计查询</h3>
                </div>
                <form class="form-horizontal" id="app" method="post" action="{{ url('/user/data') }}">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'v' ? 'checked' : '' }} type="radio" name="u" id="v" value="v" title="版本（v）"> 版本（v）
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'lang' ? 'checked' : '' }} type="radio" name="u" id="lang" value="lang" title="语言（lang）"> 语言（lang）
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'brand' ? 'checked' : '' }} type="radio" name="u" id="brand" value="brand" title="品牌（brand）"> 品牌（brand）
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'device' ? 'checked' : '' }} type="radio" name="u" id="device" value="device" title="设备（device）"> 设备（device）
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'ov' ? 'checked' : '' }} type="radio" name="u" id="ov" value="ov" title="版本（ov）"> 版本（ov）
                            </label>
                        </div>

                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'country' ? 'checked' : '' }} type="radio" name="u" id="country" value="country" title="国家（country）"> 国家（country）
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'area' ? 'checked' : '' }} type="radio" name="u" id="area" value="area" title="地区（area）"> 地区（area）
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'region' ? 'checked' : '' }} type="radio" name="u" id="region" value="region" title="省（region）"> 省（region）
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'city' ? 'checked' : '' }} type="radio" name="u" id="city" value="city" title="城市（city）"> 城市（city）
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input {{ isset($u) && $u == 'isp' ? 'checked' : '' }} type="radio" name="u" id="isp" value="isp" title="运营商（isp）"> 运营商（isp）
                            </label>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(function(){
            $(':radio').on('change',function(){
                $('#app').submit();
            });

            var u = '{{ isset($u) ? $u : '' }}';
            if (u == '') return;
            $('input[name=u]').each(function(){
                if (this.value == u){
                    $('#query-head').text(this.title);
                    return false;
                }
            });

        })
    </script>
@endsection