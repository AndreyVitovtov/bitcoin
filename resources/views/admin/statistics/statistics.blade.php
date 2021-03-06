@extends("admin.template")

@section("title")
    @lang('pages.statistics')
@endsection

@section("h3")
    <h3>@lang('pages.statistics')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/statistics.css')}}">

    @foreach($statistics as $key => $value)
        <div class="chart_statistics_2">
            <div id="chart_{{ $key }}"></div>
        </div>
    @endforeach

    <script>
        let statistics = {};
        @foreach($statistics as $key => $value)
            statistics['{{ $key }}'] = {!! json_encode($value) !!}
        @endforeach

    //Messengers
        chart.options.title = "@lang('pages.statistics_count_users_messengers')";
        chart.options.colors = ['#0088cc', '#665CAC', '#4e8094', '#55516a', '#613034'];
        chart.data = [
            ['', 'Telegram', 'Viber', 'Unsubscribed Telegram', 'Unsubscribed Viber', 'Not start'],
            [
                "@lang('pages.statistics_users_count')",
                statistics.messengers.Telegram,
                statistics.messengers.Viber,
                statistics.messengers.Telegram_U,
                statistics.messengers.Viber_U,
                statistics.messengers.not_start
            ]
        ];
        chart.drawBar('chart_messengers');

    //Countries
        statistics.countries.unshift(['Country', 'Count']);
        chart.options.title = "@lang('pages.statistics_count_users')";
        chart.data = statistics.countries;
        chart.drawPie('chart_countries');

    //Visits
        statistics.visits.unshift(['Date', "@lang('pages.statistics_count')"]);
        chart.options.title = "@lang('pages.statistics_count_users_visits')";
        chart.data = statistics.visits;
        chart.drawColumn('chart_visits');

    //Bitcoin
        chart.options.title = "@lang('pages.statistics_bitcoin')";
        chart.options.colors = ['#0088cc', '#0abd20', '#ffee13'];
        chart.data = [
            ['', '@lang('pages.bitcoin_total')', '@lang('pages.bitcoin_paid')', '@lang('pages.bitcoin_waiting')'],
            ["@lang('pages.satoshi')", statistics.bitcoin.total, statistics.bitcoin.paid, statistics.bitcoin.waiting]
        ];
        chart.drawBar('chart_bitcoin');
    </script>
@endsection
