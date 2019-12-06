<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">

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
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{{ $text_for_mail }}</div>
                @if ($lang == 'hr')
                    @include('admin.visitors.smjernice')
                @elseif ($lang == 'en')
                    @include('admin.visitors.smjernice_en')
                @elseif ($lang == 'de')
                    @include('admin.visitors.smjernice_de')
                @endif
            </div>
        </div>
    </body>	
</html>