<div class="panel panel-default visible-lg visible-md">
    <div class="panel-heading">
        <h3 class="panel-title">Голосование</h3>
    </div>
    <div class="panel-body">
        <h4>@oprosName@</h4>
        <form action="/opros/" method="post" role="form">
            @oprosContent@
            <div>
                <button type="submit" class="btn btn-primary">Голосовать</button>
                <a href="/opros/" class="btn btn-default">Результаты</a>
            </div>
        </form>
    </div>
</div>
