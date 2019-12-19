
var lineChartData = {
    datasets: [
        {
            label: "Dataset",
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

$().ready(function() {


    // Поиск заказа
    $(".btn-order-search").on('click', function() {
        $('#order_search').submit();
    });

    // Поиск заказа - очистка
    $(".btn-order-cancel").on('click', function() {
        window.location.replace('?path=' + $.getUrlVar('path'));
    });

    // datetimepicker
    if ($(".date").length) {
        $(".date").datetimepicker({
            format: 'yyyy-mm-dd',
            pickerPosition: 'bottom-left',
            language: 'ru',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
    }

    if ($('#canvas').length) {
        lineChartData.datasets[0].data = JSON.parse($("#canvas").attr('data-value'));
        lineChartData.labels = JSON.parse($("#canvas").attr('data-label'));
        var title = $("#canvas").attr('data-title');

        var ctx = $("#canvas").get(0).getContext("2d");
        lineChart = new Chart(ctx).Line(lineChartData, {
            animation: false,
            responsive: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + title
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
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + title
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
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + title
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
                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + title
            });
        }

        $('ul.canvas-select > li').removeClass('disabled');
        $(this).parent('li').addClass('disabled');
    });


});