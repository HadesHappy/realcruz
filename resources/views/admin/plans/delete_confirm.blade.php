<h4>{{ trans('messages.delete_plan_confirm_warning') }}</h4>
<ul class="modern-listing">
    @foreach ($plans->get() as $plan)
        <li class="d-flex align-items-center">
            <i class="material-symbols-rounded fs-4 me-3 text-danger">
error_outline
            </i>
            <div>
                <h5 class="text-danger mb-1">{{ $plan->name }}</h5>
                <p class="text-muted">
                    @if ($plan->subscriptionsCount())
                        <span class="text-bold text-danger">{{ $plan->subscriptionsCount() }}</span> {{ trans('messages.subscription') }}<pp>,</pp>
                    @endif
                </p>  
            </div>                      
        </li>
    @endforeach
</ul>