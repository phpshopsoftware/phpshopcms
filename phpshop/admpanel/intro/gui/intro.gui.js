
var lineChartData = {
    datasets: [
        {
            label: "Отчеты",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)"
        }
    ]
};

var lineChartData2 = {
    datasets: [
        {
            label: "Отчеты",
            fillColor: "rgba(255, 217, 99,0.2)",
            strokeColor: "rgba(255, 217, 99,1)",
            pointColor: "rgba(255, 217, 99,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(255, 217, 99,1)"
        }
    ]
};


function startClock() {

    var now = new Date();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    if (hour < 10) {
        hour = "0" + hour;
    }
    if (minute < 10) {
        minute = "0" + minute;
    }
    if (second < 10) {
        second = "0" + second;
    }
    timer = setInterval(function() {
        $('#clock').text(hour + ":" + minute + ":" + second);
    }, 100);
}



$(document).ready(function() {

    // Расширенный поиск товара
    $(".search").on('click', function(event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[selectID]', value: 'actionAdvanceSearch'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=catalog.search',
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                $('#selectModal .modal-dialog').removeClass('modal-lg');
                $('#selectModal .modal-title').html(locale.search_advance_title);
                $('#selectModal .modal-footer .btn-primary').html(locale.search_advance_but);
                $('#selectModal .modal-footer .btn-primary').addClass('search-send');
                $('#selectModal .modal-footer .btn-delete').addClass('hidden');
                $('#selectModal .modal-body').html(data);
                $('#selectModal').modal('show');

                $('#modal-form').attr('method', 'get');
            }

        });
    });

    // Часы
    var clock = '<span class="glyphicon glyphicon-time"></span> ';
    timer = setInterval(function() {
        var now = new Date();
        var hour = now.getHours();
        var minute = now.getMinutes();
        var second = now.getSeconds();

        if (hour < 10) {
            hour = "0" + hour;
        }
        if (minute < 10) {
            minute = "0" + minute;
        }
        if (second < 10) {
            second = "0" + second;
        }

        $('.clock').html(clock + hour + ":" + minute + ":" + second);
    }, 1000);

    if ($('#canvas').length) {
        lineChartData.datasets[0].data = JSON.parse($("#canvas").attr('data-value'));
        lineChartData.labels = JSON.parse($("#canvas").attr('data-label'));
        var currency = $("#canvas").attr('data-currency');

        var ctx = $("#canvas").get(0).getContext("2d");
        lineChart = new Chart(ctx).Line(lineChartData, {
            animation: false,
            responsive: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + currency
        });
    }

    if ($('#canvas2').length) {
        lineChartData2.datasets[0].data = JSON.parse($("#canvas2").attr('data-value'));
        lineChartData2.labels = JSON.parse($("#canvas2").attr('data-label'));
        var title = $("#canvas2").attr('data-title');
        var ctx2 = $("#canvas2").get(0).getContext("2d");
        lineChart2 = new Chart(ctx2).Line(lineChartData2, {
            animation: false,
            responsive: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + title
        });
    }

    $('.canvas-bar').on('click', function(event) {
        event.preventDefault();

        if ($(this).attr("data-canvas")) {
            lineChart2.destroy();

            lineChart2 = new Chart(ctx2).Bar(lineChartData2, {
                animation: false,
                responsive: true,
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + title
            });
        }
        else {
            lineChart.destroy();

            lineChart = new Chart(ctx).Bar(lineChartData, {
                animation: false,
                responsive: true,
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + currency
            });
        }

        $('ul.canvas-select > li').removeClass('disabled');
        $(this).parent('li').addClass('disabled');
    });


    $('.canvas-line').on('click', function(event) {
        event.preventDefault();

        if ($(this).attr("data-canvas")) {
            lineChart2.destroy();

            lineChart2 = new Chart(ctx2).Line(lineChartData2, {
                animation: false,
                responsive: true,
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + title
            });
        }
        else {
            lineChart.destroy();

            lineChart = new Chart(ctx).Line(lineChartData, {
                animation: false,
                responsive: true,
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + currency
            });
        }

        $('ul.canvas-select > li').removeClass('disabled');
        $(this).parent('li').addClass('disabled');
    });

    $('.canvas-radar').on('click', function(event) {
        event.preventDefault();

        if ($(this).attr("data-canvas")) {
            lineChart2.destroy();
            lineChart2 = new Chart(ctx2).Radar(lineChartData2, {
                animation: false,
                responsive: true,
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + title
            });
        }
        else {
            lineChart.destroy();
            lineChart = new Chart(ctx).Radar(lineChartData, {
                animation: false,
                responsive: true,
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + currency
            });
        }

        $('ul.canvas-select > li').removeClass('disabled');
        $(this).parent('li').addClass('disabled');
    });

});