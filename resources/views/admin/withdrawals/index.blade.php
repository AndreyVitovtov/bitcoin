@extends("admin.template")

@section("title")
    @lang('pages.withdrawals')
@endsection

@section("h3")
    <h3>@lang('pages.withdrawals')</h3>
@endsection

@section("main")
    <div class="overflow-X-auto">
        <table>
            <tr>
                <td>№</td>
                <td>@lang('pages.user')</td>
                <td>@lang('pages.satoshi')</td>
                <td>@lang('pages.wallet')</td>
                <td>@lang('pages.status')</td>
                <td>@lang('pages.date_time')</td>
                <td>@lang('pages.actions')</td>
            </tr>
            @foreach($withdrawals as $withdrawal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('user-profile', $withdrawal->user->id) }}" class="link">
                            {{ $withdrawal->user->username }}
                        </a>
                    </td>
                    <td>{{ $withdrawal->satoshi }}</td>
                    <td>{{ $withdrawal->comment }}</td>
                    <td>
                        @if($withdrawal->status == '1')
                            @lang('pages.paid')
                        @elseif($withdrawal->status == '-1')
                            @lang('pages.cancel')
                        @else
                            @lang('pages.expects')
                        @endif
                    </td>
                    <td>{{ $withdrawal->date_time }}</td>
                    <td class="actions">
                        <div>
                            @if($withdrawal->status == '0')
                            <form action="{{ route('withdrawal-paid') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $withdrawal->id }}">
                                <button>
                                    <i class='icon-money-2'></i>
                                </button>
                            </form>
                                <form action="{{ route('withdrawal-cancel') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $withdrawal->id }}">
                                <button>
                                    <i class='icon-cancel-7'></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
