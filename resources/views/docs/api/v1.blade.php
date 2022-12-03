@extends('layouts.core.' . $view)

@section('title', trans('messages.API_Documentation'))

@section('content')

    <div class="api-page mt-5">
        <h1>{{ trans('messages.API_Documentation') }}</h1>
        <p class="alert alert-info">{!! trans('messages.api_token_guide', ["link" => action("Api\MailListController@index", ["api_token" => "YOUR_API_TOKEN"])]) !!}</p>

        @foreach (\Acelle\Library\ApiHelper::docs() as $box)
            @if ($box['view'] == $view)
                <h2 class="mt-5 mb-20" style='text-transform: uppercase;'>{{ $box['title'] }}</h2>
                <table class="table table-box pml-table table-log api-doc-table">
                    <tr>
                        <th width="1%" class="text-nowrap">{{ trans('messages.HTTP_method') }}</th>
                        <th width="40%">{{ trans('messages.Endpoint') }}</th>
                        <th>{{ trans('messages.Function') }}</th>
                    </tr>
                    @foreach ($box['functions'] as $function)
                        <tr>
                            <td>
                                <span class="label label-flat {{
                                    $function['method'] == 'POST' ?
                                    'bg-primary' :
                                    ($function['method'] == 'GET' ?
                                    'bg-info' :
                                    ($function['method'] == 'PATCH' ?
                                    'bg-success' : 'bg-danger'))
                                }}">{{ trans('messages.' . $function['method']) }}</span>
                            </td>
                            <td>
                              <a href="#more" class="toogle-api">{{ $function['uri'] }}</a>
                            </td>
                            <td>
                                {{ $function['description'] }}
                            </td>
                        </tr>
                        <tr style="display:none;" class="api-detail">
                            <td></td>
                            <td>
                                <div>
                                    <div class="description detailed">                                        
                                        @if ($function['parameters'])
                                            <h4>{{ trans('messages.parameters') }}</h4>
                                                <div class="list"><dl>
                                                    @foreach ($function['parameters'] as $parameter)
                                                        <dt><var>{{ $parameter['name'] }}
                                                            @if (isset($parameter['optional']))
                                                                 &nbsp;&nbsp;<span class="text-muted2 text-normal">{{ trans('messages.optional') }}

                                                                @if (isset($parameter['default']))
                                                                     - default: {{ $parameter['default'] }}
                                                                @endif
                                                                </span>
                                                            @endif
                                                        </var></dt></dt>
                                                        <dd>{!! $parameter['description'] !!}</dd>
                                                    @endforeach
                                                </dl></div>
                                        @endif
                                        <h4>{{ trans('messages.returns') }}</h4>
                                        <div class="list">
                                            {{ $function['returns'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                              <td>
                                  <h4>Example:</h4>
                                    @if (is_array($function['example']))
                                        @foreach ($function['example'] as $example)
                                            <pre class="mb-3"><code>{!! $example !!}</code></pre>
                                        @endforeach
                                    @else
                                        <pre class=""><code>{!! $function['example'] !!}</code></pre>
                                    @endif
                                  <br>
                                    @if (isset($function['help']))
                                        {!! $function['help'] !!}
                                    @endif
                              </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        @endforeach

    </div>

    <script>
      $(document).ready(function() {
        $(".toogle-api").click(function() {
          $(this).parents("tr").next().toggle();
        });
      });
    </script>

@endsection
