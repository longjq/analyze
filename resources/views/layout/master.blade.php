<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/navbar-static-top.css') }}" rel="stylesheet">
    <link href="http://cdn.bootcss.com/bootstrap-datetimepicker/4.15.35/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('js/plugins/Font-Awesome-3.2.1/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="{{ asset('js/ie8-responsive-file-warning.js') }}"></script><![endif]-->
    <script src="{{ asset('js/ie-emulation-modes-warning.js') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    {{--<script src="{{ asset('js/plugins/artTemplate/artTemplate.js') }}"></script>--}}
    <script src="http://cdn.bootcss.com/vue/1.0.24/vue.js"></script>
</head>

<body>

<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">数据统计</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="/dash">首页</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">用户数据 <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/charts/users_new">用户新增折线图</a></li>
                        <li><a href="/charts/users_hot">用户活跃折线图</a></li>
                        <li><a href="/user/data">用户资料</a></li>
                        <li><a href="/user/data_export">用户资料导出</a></li>
                        <li><a href="/charts/users_list">平均存活率</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">应用数据 <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/app/packages">应用用户查询</a></li>
                        <li><a href="/app/users">应用用户统计</a></li>
                        <li><a href="/app/events">历史应用</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                {{--<li><a href="../navbar/">Default</a></li>--}}
                {{--<li class="active"><a href="./">Static top <span class="sr-only">(current)</span></a></li>--}}
                <li><p class="navbar-text" id="showtimes"></p></li>
                <li><p class="navbar-text">{{ \Auth::user()->name }}</p></li>
                <li><a href="{{ url('/logout') }}">退出</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>


<div class="container">
    <!-- Main component for a primary marketing message or call to action -->
    @yield('content')
</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="http://cdn.bootcss.com/highcharts/4.2.5/highcharts.js"></script>
<script src="http://cdn.bootcss.com/moment.js/2.13.0/moment.min.js"></script>
<script src="http://cdn.bootcss.com/moment.js/2.13.0/locale/zh-cn.js"></script>
<script src="http://cdn.bootcss.com/bootstrap-datetimepicker/4.15.35/js/bootstrap-datetimepicker.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ asset('js/ie10-viewport-bug-workaround.js') }}"></script>
@section('js')
@show
<script>
    function show_cur_times(){
//获取当前日期
        var date_time = new Date();
        //定义星期
        var week;
        //switch判断
        switch (date_time.getDay()){
            case 1: week="星期一"; break;
            case 2: week="星期二"; break;
            case 3: week="星期三"; break;
            case 4: week="星期四"; break;
            case 5: week="星期五"; break;
            case 6: week="星期六"; break;
            default:week="星期天"; break;
        }

        //年
        var year = date_time.getFullYear();
        //判断小于10，前面补0
        if(year<10){
            year="0"+year;
        }

        //月
        var month = date_time.getMonth()+1;
        //判断小于10，前面补0
        if(month<10){
            month="0"+month;
        }

        //日
        var day = date_time.getDate();
        //判断小于10，前面补0
        if(day<10){
            day="0"+day;
        }

        //时
        var hours =date_time.getHours();
        //判断小于10，前面补0
        if(hours<10){
            hours="0"+hours;
        }

        //分
        var minutes =date_time.getMinutes();
        //判断小于10，前面补0
        if(minutes<10){
            minutes="0"+minutes;
        }

        //秒
        var seconds=date_time.getSeconds();
        //判断小于10，前面补0
        if(seconds<10){
            seconds="0"+seconds;
        }

        //拼接年月日时分秒
        var date_str = year+"年"+month+"月"+day+"日 "+hours+":"+minutes+":"+seconds+" "+week;

        //显示在id为showtimes的容器里
        document.getElementById("showtimes").innerHTML= date_str;
    }
    //设置1秒调用一次show_cur_times函数
    setInterval("show_cur_times()",100);
</script>
</body>
</html>
