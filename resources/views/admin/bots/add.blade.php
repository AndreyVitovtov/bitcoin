@extends("admin.template")

@section("title")
    @lang('pages.add_bot')
@endsection

@section("h3")
    <h3>@lang('pages.add_bot')</h3>
@endsection

@section("main")
    <div>
        <form action="{{ route('add-bot-save') }}", method="POST">
            @csrf
            <div>
                <label for="select-messenger">@lang('pages.select_messenger')</label>
                <select name="messenger" id="select-messenger">
                    <option value="viber">Viber</option>
                    <option value="telegram">Telegram</option>
                </select>
            </div>
            <div>
                <label for="select-language">@lang('pages.select_language')</label>
                <select name="language" id="select-language">
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="token">@lang('pages.token')</label>
                <input type="text" name="token" id="token">
            </div>
            <div>
                <label for="name">@lang('pages.name')</label>
                <input type="text" name="name">
            </div>
            <br>
            <input type="submit" value="@lang('pages.save')" class="button">
        </form>
    </div>
@endsection
