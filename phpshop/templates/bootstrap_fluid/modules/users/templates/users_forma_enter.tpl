<p class="alert alert-danger hide @user_check@" role="alert">@userMessage@</p>
<form method="post" class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-sm-3 control-label">Пользователь</label>
        <div class="col-xs-4">
            <span class="btn btn-success"><span class="glyphicon glyphicon-user"></span>  @userName@</span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Зарегистрирован</label>
        <div class="col-xs-4">
            <span class="btn btn-default"><span class="glyphicon glyphicon-calendar"></span>  @userDate@</span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Email</label>
        <div class="col-xs-4">
            <input type="email" class="form-control" name="mail" value="@userMail@" required="">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Новый пароль</label>
        <div class="col-xs-4">
            <input type="password" class="form-control" name="password">
        </div>
    </div>

    @userContent@

    <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-xs-6">
            <input class="btn btn-default" type="submit" name="exit_user" value="Выйти">
            <input class="btn btn-primary" type="submit" name="update_user" value="Изменить данные"> 
             
        </div>
    </div>
</form>
