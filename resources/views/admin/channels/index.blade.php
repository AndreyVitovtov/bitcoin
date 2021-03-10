@extends("admin.template")

@section("title")
    @lang('pages.channels')
@endsection

@section("h3")
    <h3>@lang('pages.channels')</h3>
@endsection

@section("main")
    <div>
        <form action="{{ route('channels-save') }}" method="POST">
            @csrf
            <table>
                <tr>
                    <td>
                        @lang('pages.name')
                    </td>
                    <td>
                        @lang('pages.messenger')
                    </td>
                    <td>
                        @lang('pages.language')
                    </td>
                    <td>
                        @lang('pages.channel_link')
                    </td>
                    <td>
                        @lang('pages.channel_id')
                    </td>
                </tr>
                @foreach($bots as $bot)
                    <tr>
                        <td>{{ $bot->name }}</td>
                        <td>{{ $bot->messenger }}</td>
                        <td>{{ base64_decode($bot->language->emoji) }} {{ $bot->language->name }}</td>
                        <td>
                            <input type="text" name="name[{{ $bot->id }}]" value="{{ $bot->channel->channels_name ?? ''}}">
                        </td>
                        <td>
                            @if($bot->messenger == 'telegram')
                                <input type="text" name="id[{{ $bot->id }}]" value="{{ $bot->channel->channels_id ?? ''}}">
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            <br>
            <input type="submit" value="@lang('pages.save')" class="button">
        </form>
    </div>
@endsection
