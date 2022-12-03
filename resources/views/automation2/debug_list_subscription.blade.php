@extends('layouts.core.empty')

@section('content')
	
    <div>
        <ul style="float:left" class="pagination" role="navigation">
            <li class="page-item">
                <a href="{{ action('Automation2Controller@debug', ['uid' => $automation->uid, 'orderBy' => 'triggered_at', 'orderDir' => 'DESC']) }}">Sort By Trigger Date</a>
            </li>
        </ul>
        {{ $subscribers->onEachSide(5)->appends(request()->input())->links() }}
    </div>

	<table class="table table-box pml-table table-log mt-10">
        <tbody>
	        <tr>
	            <th width="50%">Subscriber</th>
	            <th>Subscribe At</th>
	            <th>Triggered At</th>
	        </tr>
	        @foreach($subscribers as $subscriber)
	        <tr>
                <td>
                    <span class="no-margin kq_search">
                        {{ $subscriber->email }}
                        <span class="label label-flat bg-{{ $subscriber->status }}">{{ $subscriber->status }}</span>
                    </span>
                </td>
                <td>
                    <span class="no-margin kq_search">
                        {{ $subscriber->created_at }}
                    </span>
                </td>
                <td>
                    <span class="no-margin kq_search">
                        @if (is_null($subscriber->auto_trigger_id))
                            <a href="{{ action('Automation2Controller@triggerNow', [ 'automation' => $automation->uid, 'subscriber' => $subscriber->uid ]) }}">Trigger Now</a>
                        @else
                            <a href="{{ action('AutoTrigger@show', [ 'id' => $subscriber->auto_trigger_id ]) }}">{{ $subscriber->triggered_at }}</a>
                        @endif
                    </span>
                </td>
            </tr>
            @endforeach
    	</tbody>
    </table>
@endsection