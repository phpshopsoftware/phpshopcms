<p class="alert alert-danger hide @user_check@" role="alert">@usersError@</p>
<form method="post" name="user_forma" id="user_forma" action="/user/" class="template-sm">
    <div class="form-group">
        <label>Логин</label>
        <input type="text" name="login" value="@php  echo $_POST[login]; php@" class="form-control" required=""> <span id="helpBlock" class="help-block">* не менее 2 символов</span>
    </div>
    <div class="form-group">
        <label>Пароль</label>
        <input type="password" name="password" value="@php  echo $_POST[password]; php@" class="form-control" required=""> <span id="helpBlock" class="help-block">* не менее 4 символов</span>
    </div>
    <div class="form-group">
        <label>E-mail</label>
        <input type="email" name="mail" value="@php  echo $_POST[password]; php@" class="form-control" required=""> <span id="helpBlock" class="help-block">* требуется активация пользователя</span>
    </div>
    <div class="form-group">
        <label>Ф.И.О.</label>
        <input type="text" name="dop_ФИО" value="@php  echo $_POST[dop_ФИО]; php@" class="form-control"> 
    </div>
    <div class="form-group">
        <label>Адрес</label>
        <input type="text" name="dop_Адрес" value="@php  echo $_POST[dop_Адрес]; php@" class="form-control"> 
    </div>
    
@captchaCommentStart@
<div class="form-group">
    <img src="phpshop/captcha3.php" alt="" border="0" align="left" style="margin-right:10px"> <input type="text" name="key" class="form-control" placeholder="Код с картинки..." style="width:130px" required="">
</div>
@captchaCommentEnd@
<input type="submit" name="add_user" class="btn btn-primary pull-right" value="Регистрация">
</form>
