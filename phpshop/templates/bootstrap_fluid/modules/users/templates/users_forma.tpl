
<p class="alert alert-danger hide @user_check@" role="alert">@activationNotice@</p>

<form method="post" name="user_forma" action="/user/">
    <p class="input-group">
        <span class="input-group-addon glyphicon glyphicon-user" id="basic-addon1"></span>
        <input type="text" name="login"  class="form-control" placeholder="������������" aria-describedby="basic-addon1">
    </p>

    <p class="input-group">
        <span class="input-group-addon glyphicon glyphicon-lock" id="basic-addon1"></span>
        <input type="password" name="password" name="login" class="form-control" placeholder="������" aria-describedby="basic-addon1">
    </p>


<p>
    <button type="submit" class="btn btn-primary input-sm pull-right" name="send">����</button>
    <a href="/user/register_user.html" title="�����������" class="small">�����������</a><br>
    <a href="/user/sendpassword_user.html"  title="������ ������?" class="small">������ ������?</a>
    <input type="hidden" value="1" name="enter_user">
</p>
</form>
