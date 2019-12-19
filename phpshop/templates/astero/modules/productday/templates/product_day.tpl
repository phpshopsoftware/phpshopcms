
<div class="panel panel-default">
    <div class="panel-heading">Товар дня</div>
    <div class="panel-body">

        <a href="/shop/UID_@productDayId@.html">
            <img class="media-object" src="@productDayPicSmall@" alt="@productDayName@">
        </a>

        <h4 class="media-heading"><a href="/shop/UID_@productDayId@.html">@productDayName@</a></h4>
        @productDayDescription@
        <h3>@productDayPrice@ <span class="rubznak">@productDayCurrency@</span> <sup class="text-muted small"><s>@productDayPriceN@</s></sup></h3>

        <div class="alert alert-warning" style="margin-bottom: 0px">
            <div class="row">
                <div class="col-md-3 text-center">
                    <h4 id="timer-number-days">0</h4>
                    <small id="timer-text-days">дней</small>
                </div>
                <div class="col-md-3 text-center">
                    <h4 id="timer-number-hours">@productDayHourGood@</h4>
                    <small id="timer-text-hours">часов</small>
                </div>
                <div class="col-md-3 text-center">
                    <h4 id="timer-number-minutes">@productDayMinuteGood@</h4>
                    <small id="timer-text-minutes">минут</small>
                </div>
                <div class="col-md-3 text-center">
                    <h4 id="timer-number-seconds">@productDaySecondGood@</h4>
                    <small id="timer-text-seconds">секунд</small>
                </div>
            </div>
        </div>

    </div>
</div>

<script >

    $().ready(function() {
        
        setInterval(function() {
            
            var h = $("#timer-number-hours").html();
            var m = $("#timer-number-minutes").html();
            var s = parseInt($("#timer-number-seconds").html());

            if (m != "") {
                if (s == 0) {
                    if (m == 0) {
                        if (h == 0) {
                            return;
                        }
                        h--;
                        m = 60;
                        if (h < 10)
                            h = "0" + h;
                    }
                    m--;
                    if (m < 10)
                        m = "0" + m;
                    s = 59;
                }
                else
                    s--;
                if (s < 10)
                    s = "0" + s;

                $("#timer-number-hours").html(h);
                $("#timer-number-minutes").html(m);
                $("#timer-number-seconds").html(s);
            }
        }, 1000);
    });

</script>