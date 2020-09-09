var
    wsport    = 9000,
    socket    = io('http://' + window.location.hostname + ':' + wsport),
    container = document.getElementById('ws-content'),
    chart     = null;


socket.on("public:App\\Events\\PriceUpdated", function (data) {
    data.socket = undefined;
    container.innerHTML = '<pre class="col-md-12">'+ JSON.stringify(data, null ,4) +'</pre>';
    if(chart != null) {
        chart.addPoint(data.lastPrice, true, true);
    }
});

Highcharts.setOptions({
	global: {
		useUTC: false
	}
});

$.getJSON('/history', function (data) {

    // create the chart
    Highcharts.stockChart('container', {

        chart: {
            events: {
                load: function () {
                    chart = this.series[0];
                }
            }
        },
        
        title: {
            text: 'Live BTC/IDR Price'
        },

        rangeSelector: {
            buttons: [
                {
                    type: 'minute',
                    count: 1,
                    text: '1m'
                },
                {
                    type: 'minute',
                    count: 3,
                    text: '3m'
                },
                {
                    type: 'minute',
                    count: 5,
                    text: '5m'
                },
                {
                    type: 'minute',
                    count: 15,
                    text: '15m'
                },
                {
                    type: 'hour',
                    count: 1,
                    text: '1h'
                },
                {
                    type: 'day',
                    count: 1,
                    text: '1D'
                },
                {
                    type: 'all',
                    count: 1,
                    text: 'All'
                }
            ],
            selected: 1,
            inputEnabled: false
        },

        series: [{
            name: 'BTC/IDR',
            data: data,
            tooltip: {
                valueDecimals: 2
            }
        }]
    });
});
