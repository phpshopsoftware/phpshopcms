
<ol class="breadcrumb hidden-xs">
    <li><a href="/" >�������</a></li>
    <li class="active">����������� �����</li>
</ol>

<div class="page-header hidden-xs">
    <h2>����������� �����</h2>
</div>

<div class="well">
    <form  action="/search/" method="post" role="form">

        <div class="input-group">
            <input name="words" maxlength="50" class="form-control" placeholder="������.." required="" type="search" value="@searchString@">
            <div class="input-group-btn">
                <button type="submit" class="btn btn-info" tabindex="-1"><span class="glyphicon glyphicon-search"></span></button>
            </div>
        </div>
        <span id="helpBlock" class="help-block">

            <input type="hidden" value="0" name="cat" id="cat">

            <div class="btn-group">
                ������� ������: @searchTarget@
            </div>
        </span>
    </form>
</div>

<div class="news-list">
    @productPageDis@
</div>

@searchPageNav@
