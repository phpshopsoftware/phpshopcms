

<p class="text-warning">@Error@</p>
<form role="form" method="post" name="forma_message" class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label">Заголовок</label>
        <div class="col-sm-10">
            <input type="text" name="subject" value="@php  echo $_POST[subject]; php@" class="form-control" required="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Имя</label>
        <div class="col-sm-10">
            <input type="text" name="nameP" value="@php  echo $_POST[nameP]; php@" class="form-control"  required="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">E-mail</label>
        <div class="col-sm-10">
            <input type="email" name="mail" value="@php  echo $_POST[mail]; php@" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Телефон</label>
        <div class="col-sm-10">
            <input type="text" name="tel" value="@php  echo $_POST[tel]; php@" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Сообщение</label>
        <div class="col-sm-10">
            <textarea name="message" class="form-control" required="">@php  echo $_POST[message]; php@</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-6 col-sm-offset-2 col-sm-5">
            <img src="phpshop/captcha3.php" alt="" border="0" align="left" style="margin-right:10px"> 
            <input type="text" name="key"   class="form-control" id="exampleInputEmail1" placeholder="Код..." style="width:70px" required="">
        </div>
        <div class="col-xs-6 col-sm-5">
            <span class="pull-right">
                <input type="hidden" name="send_f" value="ok">
                <button type="submit" class="btn btn-main">Отправить</button>
            </span>
        </div>
    </div>
</form>    