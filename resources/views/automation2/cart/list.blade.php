@extends('layouts.popup.small')

@section('content')     
    <a class="mb-4 d-flex align-items-center back-to-automation" href="javascript:;"
        onclick="timelinePopup.load('{{ action('Automation2Controller@cartStats', $automation->uid) }}')"
    >
        <span class="material-symbols-rounded me-2">
            arrow_back
            </span>
        <span>{{ trans('messages.automation.back_to_overview') }}</span>
    </a>

    <div class="d-flex">
        <div class="pr-4">
            <h5 class="mt-0">Your store currently has <strong class="font-weight-semibold">2</strong> carts</h5>
            <p>If users do not proceed with buying
                <br>Acelle will shoot a notification after <strong class="font-weight-semibold">12 hours</strong></p>
        </div>
        <div class="ml-auto pl-4">
            <button class="btn btn-secondary" onclick="timelinePopup.load()">Refresh</button>
        </div>
    </div>

    <div class="cart-list">
        <div class="cart d-flex">
            <div class="cicon mr-4 pr-3">
                <svg style="width:45px;height:45px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 512 512" enable-background="new 0 0 512 512">
                    <g>
                      <g>
                        <path d="m490.1,94.3h-325.6c-7.8,0-12.4,5.5-10.4,13.6l47,161.7c1,4.2 5.2,7.3 10.4,7.3h232.7c5.2,0 9.4-3.1 10.4-7.3l45.9-161.7c1.8-5.3-1-13.6-10.4-13.6zm-53.2,160.7h-217.1l-9.4-32.3h235.9l-9.4,32.3zm14.6-53.3h-247.7l-9.3-32.3h266.4l-9.4,32.3zm15.6-54.2h-278.6l-9.4-32.3h297.5l-9.5,32.3z"/>
                        <path d="M107.1,24.3c-1-5.2-5.2-8.3-10.4-8.3H11v20.9h77.2l71,285.9c1,5.2,5.2,8.3,10.4,8.3h275.5v-21.9H178L107.1,24.3z"/>
                        <rect width="243.2" x="169.7" y="363.5" height="20.9"/>
                        <path d="m400.3,412.5c-23,0-41.7,18.8-41.7,41.7 0,23 18.8,41.7 41.7,41.7s41.7-18.8 41.7-41.7c0.1-22.9-18.7-41.7-41.7-41.7zm0,61.6c-10.4,0-19.8-9.4-19.8-19.8 0-10.4 8.3-19.8 19.8-19.8 11.5,0 19.8,9.4 19.8,19.8 0.1,10.4-9.3,19.8-19.8,19.8z"/>
                        <path d="m197.9,412.5c-23,0-41.7,18.8-41.7,41.7 0,23 18.8,41.7 41.7,41.7s41.7-18.8 41.7-41.7c2.84217e-14-22.9-18.8-41.7-41.7-41.7zm0,61.6c-10.4,0-19.8-9.4-19.8-19.8 0-10.4 8.3-19.8 19.8-19.8 10.4,0 19.8,9.4 19.8,19.8-2.84217e-14,10.4-9.4,19.8-19.8,19.8z"/>
                      </g>
                    </g>
                </svg>
            </div>
            <div class="c-content" style="width:100%">
                <div class="top-info d-flex mb-2">
                    <div class="customer">
                        <div class="font-weight-semibold">Louis Louis</div>
                        <div><a href="lousi@email.com">lousi@email.com</a></div>
                    </div>
                    <div class="total ml-auto">
                        <div class="font-weight-bold">$44</div>
                        <div class="text-muted">Total</div>
                    </div>
                </div>
                <div class="c-items small">
                    <div class="c-item d-flex mb-3">
                        <div class="info">
                            <div>1 x This is an awesome book</div>
                            <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                        </div>
                        <div class="status">10 minutes ago</div>
                        <div class="pic">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU" width="40px" />
                        </div>
                    </div>
                    <div class="c-item d-flex mb-3">
                        <div class="info">
                            <div>1 x This is an awesome book</div>
                            <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                        </div>
                        <div class="status">10 minutes ago</div>
                        <div class="pic">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU" width="40px" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cart d-flex">
            <div class="cicon mr-4 pr-3">
                <svg style="width:45px;height:45px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 512 512" enable-background="new 0 0 512 512">
                    <g>
                      <g>
                        <path d="m490.1,94.3h-325.6c-7.8,0-12.4,5.5-10.4,13.6l47,161.7c1,4.2 5.2,7.3 10.4,7.3h232.7c5.2,0 9.4-3.1 10.4-7.3l45.9-161.7c1.8-5.3-1-13.6-10.4-13.6zm-53.2,160.7h-217.1l-9.4-32.3h235.9l-9.4,32.3zm14.6-53.3h-247.7l-9.3-32.3h266.4l-9.4,32.3zm15.6-54.2h-278.6l-9.4-32.3h297.5l-9.5,32.3z"/>
                        <path d="M107.1,24.3c-1-5.2-5.2-8.3-10.4-8.3H11v20.9h77.2l71,285.9c1,5.2,5.2,8.3,10.4,8.3h275.5v-21.9H178L107.1,24.3z"/>
                        <rect width="243.2" x="169.7" y="363.5" height="20.9"/>
                        <path d="m400.3,412.5c-23,0-41.7,18.8-41.7,41.7 0,23 18.8,41.7 41.7,41.7s41.7-18.8 41.7-41.7c0.1-22.9-18.7-41.7-41.7-41.7zm0,61.6c-10.4,0-19.8-9.4-19.8-19.8 0-10.4 8.3-19.8 19.8-19.8 11.5,0 19.8,9.4 19.8,19.8 0.1,10.4-9.3,19.8-19.8,19.8z"/>
                        <path d="m197.9,412.5c-23,0-41.7,18.8-41.7,41.7 0,23 18.8,41.7 41.7,41.7s41.7-18.8 41.7-41.7c2.84217e-14-22.9-18.8-41.7-41.7-41.7zm0,61.6c-10.4,0-19.8-9.4-19.8-19.8 0-10.4 8.3-19.8 19.8-19.8 10.4,0 19.8,9.4 19.8,19.8-2.84217e-14,10.4-9.4,19.8-19.8,19.8z"/>
                      </g>
                    </g>
                </svg>
            </div>
            <div class="c-content" style="width:100%">
                <div class="top-info d-flex mb-2">
                    <div class="customer">
                        <div class="font-weight-semibold">Louis Louis</div>
                        <div><a href="lousi@email.com">lousi@email.com</a></div>
                    </div>
                    <div class="total ml-auto">
                        <div class="font-weight-bold">$44</div>
                        <div class="text-muted">Total</div>
                    </div>
                </div>
                <div class="c-items small">
                    <div class="c-item d-flex mb-3">
                        <div class="info">
                            <div>1 x This is an awesome book</div>
                            <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                        </div>
                        <div class="status">10 minutes ago</div>
                        <div class="pic">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU" width="40px" />
                        </div>
                    </div>
                    <div class="c-item d-flex mb-3">
                        <div class="info">
                            <div>1 x This is an awesome book</div>
                            <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                        </div>
                        <div class="status">10 minutes ago</div>
                        <div class="pic">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU" width="40px" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection