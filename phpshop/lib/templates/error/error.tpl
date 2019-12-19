<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="windows-1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@title@ ������</title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <style>
            html {
                position: relative;
                min-height: 100%;
            }
            body {
                margin-bottom: 60px;
            }
            .footer {
                position: absolute;
                bottom: 0;
                width: 100%;
                height: 60px;
                background-color: #f5f5f5;
            }
            .container {
                width: auto;
                max-width: 680px;
                padding: 0 15px;
            }
            .container .text-muted {
                margin: 20px 0;
            }
            a .glyphicon{
                padding-right: 3px;
            }
        </style>
        <script type="text/javascript">
  var GOOG_FIXURL_LANG = 'ru';
</script>
    </head>
    <body role="document">
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <div class="container">
            <div class="page-header">
                <h1 class="text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> @title@ ������</h1>
            </div>
            <p class="lead">@message@</p>
            
        <p>��������� ���������� �� ���������� ������ �� ������ ����� ������, �������������� ���� ��� ���������� � <a class="btn btn-info btn-xs"href="https://help.phpshop.ru" target="_blank" title="����������� ���������"><span class="glyphicon glyphicon-user"></span>����������� ���������</a></p>
        <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-alert"></span> ���� ������</h3>
                </div>
                <div class="panel-body">
                    <ul>
                        <li><b>101 ������ ����������� � ���� ������</b>
                            <ol>
                                <li>������� ��������� ����������� � ���� ������: <em>host, user_db, pass_db, dbase</em>.
                                <li>� ����� <code>phpshop/inc/config.ini</code> ��������������� ���������� ��� ���� ���� (�������� ������ ����� ���������).<br>
                                    <pre>[connect]
host="localhost";       # ��� �����
user_db="user";         # ��� ������������
pass_db="mypas";        # ������ ����
dbase="mybase";         # ��� ����</pre>
                            </ol>
                        <li><b>102 �� ����������� ����</b>
                            <ol><li>��������� ��������� ���� ������ <code>/install/</code></ol>
                        <li><b>105 ������ ������������� ����� /install</b>
                            <ol>
                                <li>������� ����� <code>/install</code>
                            </ol>
                    </ul>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <p class="text-muted text-center">
                    ������� <a href="http://phpshop.ru" target="_blank" title="�����������"><span class="glyphicon glyphicon-home"></span>�����</a> ��� ��������������� <a href="https://help.phpshop.ru" target="_blank" title="����������� ���������"><span class="glyphicon glyphicon-user"></span>����������� ����������</a>
                </p>
            </div>
        </footer>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    </body>
</html>