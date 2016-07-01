<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>数据分析 - 登录</title>

    <!-- Bootstrap core CSS -->
    <link href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/signin.css') }}" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="{{ asset('js/ie8-responsive-file-warning.js')  }}"></script><![endif]-->
    <script src="{{ asset('js/ie-emulation-modes-warning.js') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!-- {{ gethostbyname($_SERVER["SERVER_NAME"]) }} -->
<div class="container">
    <form class="form-signin" method="post" action="{{ url('/login') }}">
        {{ csrf_field() }}
        <h2 class="form-signin-heading">{!! $_SERVER['HTTP_HOST'] !!}</h2>
        <label for="inputEmail" class="sr-only">账号</label>
        <input type="text" id="inputEmail" class="form-control" name="name" placeholder="账号" required autofocus>
        <br>
        <label for="inputPassword" class="sr-only">密码</label>
        <input type="password" id="inputPassword" class="form-control" name="pass" placeholder="密码" required>
        <div class="checkbox text-right">
            <label>
                <input type="checkbox" value="remember"> 记住我
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
    </form>

</div> <!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ asset('js/ie10-viewport-bug-workaround.js') }}"></script>
</body>
</html>
