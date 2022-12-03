<div class="mc_section boxing">
    <div class="row">
        <div class="col-md-6">
            <h3 class="mt-0">{{ trans('messages.sending_servers.sending_identity') }}</h3>
            <p>
                {!! trans('messages.sending_servers.sending_identity.elasticemail.intro', ['link' => '']) !!}
            </p>
        </div>

        <div class="col-md-8">
            @if (is_null($identities))
                @include('elements._notification', [
                    'level' => 'warning',
                    'title' => 'Error fetching identities list',
                    'message' => 'Please check your connection to AWS',
                ])
            @else
                <table class="table table-box table-box-head field-list">
                    <thead>
                        <tr>
                            <td>{{ trans('messages.domain') }}</td>
                            <td>{{ trans('messages.status') }}</td>
                            <td align="center" class="xtooltip" title="Set whether or not this identity is available for all users">Available for All</td>
                            <td>Added By</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allIdentities as $domain => $attributes)
                            <tr class="odd">
                                <td>
                                    {{ $domain }}
                                </td>
                                <td>
                                    @if ($attributes['VerificationStatus'] == 'Success')
                                        <span class="label label-flat bg-active">{{ trans('messages.sending_domain_status_active') }}</span>
                                    @else
                                        <span class="label label-flat bg-inactive">{{ trans('messages.sending_domain_status_inactive') }}</span>
                                    @endif
                                    
                                </td>

                                @if (!is_null($attributes['UserId']))
                                    <td align="center"><span class="xtooltip" title="This domain is private and is available for the owner user only">Private</span></td>
                                @elseif ($attributes['VerificationStatus'] == 'Success')
                                    <td align="center">
                                        <label>
                                            @if (checkEmail($domain))
                                                <input type="checkbox" name="options[emails][]" value="{{ $domain }}" class="switchery"
                                                    {{ $attributes['Selected'] ? " checked" : "" }}
                                                />
                                            @else
                                                <input type="checkbox" name="options[domains][]" value="{{ $domain }}" class="switchery"
                                                    {{ $attributes['Selected'] ? " checked" : "" }}
                                                />
                                            @endif
                                        </label>
                                    </td>
                                @else
                                    <td align="center"></td>
                                @endif
                                <td>
                                    <a href="{{ action('Admin\CustomerController@index') }}" target="_blank">{{ $attributes['UserName'] }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="col-md-6">
            <br>
            <a  href="https://elasticemail.com/account/" role="button" target="_blank"
              class="btn btn-secondary me-2">
                {{ trans('messages.sending_serbers.add_domain') }}
            </a>
            <a  href="https://elasticemail.com/account/" role="button" target="_blank"
              class="btn btn-primary">
                {{ trans('messages.sending_serbers.go_to_elasticemail_dashboard') }}
            </a>

            <hr>
            <div class="mt-20">
                <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
                <a href="{{ action('Admin\SendingServerController@index') }}" role="button" class="btn btn-link">
                    {{ trans('messages.cancel') }}
                </a>
            </div>
        </div>
    </div>
</div>
