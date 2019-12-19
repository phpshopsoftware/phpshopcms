
<p class="alert alert-danger hide @user_check@" role="alert">@usersError@</p>

<form method="post" name="user_forma" action="/user/" class="form-inline">

    <div class="form-group">
        <input type="text" name="login" value="@php  echo $_POST[login]; php@" class="form-control"  placeholder="Логин"> или 
    </div>

    <div class="form-group">
        <input type="email" name="mail" value="@php  echo $_POST[password]; php@" class="form-control" placeholder="E-mail"> 
    </div>

    <input type="submit" name="send_user" class="btn btn-primary" value="Выслать">

</form>