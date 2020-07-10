<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ URL::asset('css/admin.css') }}" type="text/css" >
        <style>
            html, body {
                height: 100%;
            }
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #000;
                display: table;
                font-weight: 300;
                font-family: 'Lato', sans-serif;
            }
            .container {
                text-align: left;
                display: table-cell;
                vertical-align: middle;
            }
            .content {
                text-align: left;
                display: inline-block;
            }
            .title {
                font-size: 20px;
                margin-bottom: 40px;
            }
            .alert_box {
                display: flex;
                overflow: hidden;
            }
            .alert_box.video  {
                margin-bottom: 20px;
            }
            .alert_box.video .alert_icons {
                width: 100px;
            } 
            .alert_box.video .alert_text {
                width: calc(100% - 100px);
            } 
            .icon_alert {
                width: auto;
                height: 45px;
            }
            .alert_icons {
                float: left;
                width: 60px;
                text-align: center;
            }
            .alert_icons .icon_alert {
                width: 100%;
                height: auto;
            }
            .alert_text {
                float: left;
                width: calc(100% - 60px);
                margin: 0;
                padding-left: 20px;
                vertical-align: middle;
                align-self: center;

            }
            .content h1 {
                text-align: center;
                margin-bottom: 30px;
            }
            .content h5 {
                font-size: 16px;
                text-align: justify;
                font-weight: 400;
                line-height: 24px;
                margin-top: 30px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{{ $text_for_mail }}</div>
                @if ($lang == 'hr')
                    @include('admin.visitors.smjernice1')
                @elseif ($lang == 'en')
                    @include('admin.visitors.smjernice1_en')
                @elseif ($lang == 'de')
                    @include('admin.visitors.smjernice1_de')
                @endif
            </div>
        </div>
    </body>	
</html>