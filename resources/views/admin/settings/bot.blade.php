@extends("admin.template")

@section("title")
    @lang('pages.settings_bot')
@endsection

@section("h3")
    <h3>@lang('pages.settings_bot')</h3>
@endsection

@section("main")
    <div class="overflow-X-auto">
        <form action="">
            <label for="bot">@lang('pages.select_bot')</label>
            <select name="bot" id="bot">
                @foreach($bots as $bot)
                    <option value="{{ $bot->id }}"
                    @if($bot->id == $botId)
                        selected
                    @endif
                    >{{ $bot->name }} ({{ ucfirst($bot->messenger) }})</option>
                @endforeach
            </select>
            <br>
            <br>
            <input type="submit" value="@lang('pages.go')" class="button">
        </form>
        @if($botId)
            <br>
            <form action="{{ route('settings-bot-save') }}" method="POST">
                @csrf
                <input type="hidden" name="bot_id" value="{{ $botId }}">
                <div>
                    <label for="satoshi_invite">@lang('pages.satoshi_invite')</label>
                    <input type="text" name="satoshi_invite" value="{{ $settings->satoshi_invite ?? '' }}">
                </div>
                <div>
                    <label for="satoshi_invite_2">@lang('pages.satoshi_invite_2')</label>
                    <input type="text" name="satoshi_invite_2" value="{{ $settings->satoshi_invite_2 ?? '' }}">
                </div>
                <div>
                    <label for="satoshi_get_bitcoin">@lang('pages.satoshi_get_bitcoin')</label>
                    <input type="text" name="satoshi_get_bitcoin" value="{{ $settings->satoshi_get_bitcoin ?? '' }}">
                </div>
                <div>
                    <label for="number_of_referrals_for_withdrawal">
                        @lang('pages.number_of_referrals_for_withdrawal')
                    </label>
                    <input type="text" name="number_of_referrals_for_withdrawal"
                           value="{{ $settings->number_of_referrals_for_withdrawal ?? '' }}">
                </div>
                <div>
                    <label for="minimum_withdrawal_amount">
                        @lang('pages.minimum_withdrawal_amount')
                    </label>
                    <input type="text" name="minimum_withdrawal_amount"
                           value="{{ $settings->minimum_withdrawal_amount ?? '' }}">
                </div>
                <div>
                    <label for="stock_count_invite">@lang('pages.stock_count_invite')</label>
                    <input type="text" name="stock_count_invite" value="{{ $settings->stock_count_invite ?? '' }}">
                </div>
                <div>
                    <label for="stock_time">@lang('pages.stock_time')</label>
                    <input type="text" name="stock_time" value="{{ $settings->stock_time ?? '' }}">
                </div>
                <div>
                    <label for="stock_prize">@lang('pages.stock_prize')</label>
                    <input type="text" name="stock_prize" value="{{ $settings->stock_prize ?? '' }}">
                </div>
                <br>
                <input type="submit" value="@lang('pages.save')" class="button">
            </form>
        @endif
    </div>
@endsection
