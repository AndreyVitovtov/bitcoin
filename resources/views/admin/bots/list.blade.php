@extends("admin.template")

@section("title")
    @lang('pages.list_bots')
@endsection

@section("h3")
    <h3>@lang('pages.list_bots')</h3>
@endsection

@section("main")
    <div>
        <table>
            <tr>
                <td>№</td>
                <td>@lang('pages.name')</td>
                <td>@lang('pages.messenger')</td>
                <td>@lang('pages.language')</td>
                <td>@lang('pages.token')</td>
                <td>@lang('pages.actions')</td>
            </tr>
            @foreach($bots as $bot)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bot->name }}</td>
                    <td>{{ $bot->messenger }}</td>
                    <td>{{ base64_decode($bot->language->emoji) }} {{ $bot->language->name }}</td>
                    <td>{{ $bot->token }}</td>
                    <td class="actions">
                        <div>
                            <form action="{{ route('bot-delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $bot->id }}">
                                <button>
                                    <i class='icon-trash-empty'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
