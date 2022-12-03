                <ul class="nav nav-pills campaign-steps install-steps">					
                    <li class="{{ $current == 1 ? "active" : "" }} {{ $step >= 0 ? "enabled" : "" }}">
						<a href="{{ action("InstallController@systemCompatibility") }}"
							class="rounded-top rounded-3-top"
						>
							<span class="material-symbols-rounded me-1">
dns
</span> {{ trans('messages.system_compatibility') }}
						</a>
					</li>
                    <li class="{{ $current == 2 ? "active" : "" }} {{ $step >= 1 ? "enabled" : "" }}">
						<a href="{{ action("InstallController@siteInfo") }}"
							class="rounded-top rounded-3-top"
						>
							<span class="material-symbols-rounded me-1">
settings
</span> {{ trans('messages.configuration') }}
						</a>
					</li>
					<li class="{{ $current == 3 ? "active" : "" }} {{ $step >= 2 ? "enabled" : "" }}">
						<a href="{{ action("InstallController@database") }}"
							class="rounded-top rounded-3-top"
						>
							<span class="material-symbols-rounded me-1">
dns
</span> {{ trans('messages.database') }}
						</a>
					</li>
					<li class="{{ $current == 5 ? "active" : "" }} {{ $step >= 4 ? "enabled" : "" }}">
						<a href="{{ action("InstallController@cronJobs") }}"
							class="rounded-top rounded-3-top"
						>
							<span class="material-symbols-rounded me-1">
alarm
</span> {{ trans('messages.background_job') }}
						</a>
					</li>
					<li class="{{ $current == 6 ? "active" : "" }} {{ $step >= 5 ? "enabled" : "" }}"
						class="rounded-top rounded-3-top"	
					>
						<a href="{{ action("InstallController@finish") }}">
							<span class="material-symbols-rounded me-1">
task_alt
</span> {{ trans('messages.finish') }}
						</a>
					</li>
				</ul>