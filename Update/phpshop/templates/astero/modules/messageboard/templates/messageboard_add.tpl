

<ol class="breadcrumb visible-lg">
    <li><a href="/" >Главная</a></li>
    <li><a href="/board/">Доска объявлений</a></li>
    <li class="active">Форма объявления</li>
</ol>

<div class="page-header">
    <h2>Форма объявления</h2>
</div>

<form role="form" method="post" name="forma_gbook">
    <div class="form-group">
        <label>Имя</label>
        <input type="text" name="name_new" value="@userName@" maxlength="45" class="form-control" placeholder="Имя..." required="">
    </div>
    <div class="form-group">
        <label>Телефон</label>
        <input type="text" name="tel_new" maxlength="30" class="form-control" placeholder="Телефон...">
    </div>
    <div class="form-group">
        <label>E-mail</label>
        <input class="form-control" type="text" name="mail_new" value="@userMail@" maxlength="30" placeholder="E-mail">
    </div>
     <div class="form-group">
        <label>Тема</label>
        <input class="form-control" type="text" name="tema_new"  placeholder="Тема" required="">
    </div>
    <div class="form-group">
        <label>Объявление</label>
        <textarea class="form-control" name="content_new" maxlength="200" placeholder="Объявление..." required=""></textarea>
    </div>
    <div class="form-group">
        <span class="pull-right">
            <input type="hidden" name="send_gb" value="1">
            <button type="submit" class="btn btn-primary">Отправить объявление</button>
        </span>
        <img src="phpshop/captcha3.php" alt="" border="0" align="left" style="margin-right:10px"> <input type="text" name="key" class="form-control" id="exampleInputEmail1" placeholder="Код с картинки..." style="width:150px" required="">

    </div>
    
</form>
