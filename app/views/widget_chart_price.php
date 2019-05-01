
<link rel="stylesheet" href="../../css/graph.css">

<!-- Graph HTML -->
<div id="graph-wrapper">
    <div class="graph-info">
        <a href="#" id="bars"><span></span></a>
        <a href="#" id="lines" class="active"><span></span></a>
    </div>

    <div class="graph-container">
        <div id="graph-lines"></div>
        <div id="graph-bars"></div>
    </div>
</div>
<!-- end Graph HTML -->

<script src="../../js/jquery-3.2.1.min.js"></script>
<script src="../../js/jquery.flot.min.js"></script>
<script>
    $(document).ready(function () {
        // Graph Data ##############################################
        var graphData = [{
            // Returning Visits
            data: [
                [1, 14],
                [2, 5],
                [3, 17],
                [4, 14],
                [5, 12],
                [6, 17],
                [7, 17],
                [8, 321],
                [9, 16],
                [10, 12]
            ],
            color: '#77b7c5',
            points: {
                radius: 5,
                fillColor: '#77b7c5'
            }
        }];

        // Lines Graph #############################################
        $.plot($('#graph-lines'), graphData, {
            series: {
                points: {
                    show: true,
                    radius: 5
                },
                lines: {
                    show: true
                },
                shadowSize: 0
            },
            grid: {
                color: '#646464',
                borderColor: 'transparent',
                borderWidth: 20,
                hoverable: true
            },
            xaxis: {
                tickColor: 'transparent',
                tickDecimals: 2
            },
            yaxis: {
                tickSize: 100
            }
        });

        // Bars Graph ##############################################
        $.plot($('#graph-bars'), graphData, {
            series: {
                bars: {
                    show: true,
                    barWidth: .9,
                    align: 'center'
                },
                shadowSize: 0
            },
            grid: {
                color: '#646464',
                borderColor: 'transparent',
                borderWidth: 20,
                hoverable: true
            },
            xaxis: {
                tickColor: 'transparent',
                tickDecimals: 2
            },
            yaxis: {
                tickSize: 100
            }
        });

        // Graph Toggle ############################################
        $('#graph-bars').hide();
        $('#lines').on('click', function (e) {
            $('#bars').removeClass('active');
            $('#graph-bars').fadeOut();
            $(this).addClass('active');
            $('#graph-lines').fadeIn();
            e.preventDefault();
        });
        $('#bars').on('click', function (e) {
            $('#lines').removeClass('active');
            $('#graph-lines').fadeOut();
            $(this).addClass('active');
            $('#graph-bars').fadeIn().removeClass('hidden');
            e.preventDefault();
        });
        // Tooltip #################################################
        function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                top: y - 16,
                left: x + 20
            }).appendTo('body').fadeIn();
        }
        var previousPoint = null;
        $('#graph-lines, #graph-bars').bind('plothover', function (event, pos, item) {
            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
                    $('#tooltip').remove();
                    var x = item.datapoint[0],
                        y = item.datapoint[1];
                    showTooltip(item.pageX, item.pageY, y + ' visitors at ' + x + '.00h');
                }
            } else {
                $('#tooltip').remove();
                previousPoint = null;
            }
        });

    });
</script>