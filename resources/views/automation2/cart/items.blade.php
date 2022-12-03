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
        <div class="pr-4" style="width:50%">
            <select class="select">
                <option>All</option>
                <option>Sent</option>
                <option>Opened</option>
                <option>Clicked</option>
                <option selected>Purchased</option>
                <option>Added</option>
            </select>
        </div>
        <div class="ml-auto pl-4">
            <button class="btn btn-secondary" onclick="timelinePopup.load()">Refresh</button>
        </div>
    </div>
<hr>
    <div class="items-list mt-4">
        <div class="c-items">
            <div class="c-item d-flex mb-4">
                <div class="pic mr-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU"
                        width="60px" />
                </div>
                <div class="info">
                    <div>1 x This is an awesome book</div>
                    <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                    <div>Sent to: <strong class="font-weight-semibold"> Louis Pham  </strong>· <a href="">louis@email.com</a></div>
                </div>
                <div class="status ml-auto">
                    <div>10 minutes ago</div>
                    <div class="status text-success">opened</div>
                </div>
            </div>
            <div class="c-item d-flex mb-4">
                <div class="pic mr-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU"
                        width="60px" />
                </div>
                <div class="info">
                    <div>1 x This is an awesome book</div>
                    <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                    <div>Sent to: <strong class="font-weight-semibold"> Louis Pham  </strong>· <a href="">louis@email.com</a></div>
                </div>
                <div class="status ml-auto">
                    <div>10 minutes ago</div>
                    <div class="status text-grey">sent</div>
                </div>
            </div>
            <div class="c-item d-flex mb-4">
                <div class="pic mr-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU"
                        width="60px" />
                </div>
                <div class="info">
                    <div>1 x This is an awesome book</div>
                    <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                    <div>Sent to: <strong class="font-weight-semibold"> Louis Pham  </strong>· <a href="">louis@email.com</a></div>
                </div>
                <div class="status ml-auto">
                    <div>10 minutes ago</div>
                    <div class="status text-info">clicked</div>
                </div>
            </div>
            <div class="c-item d-flex mb-4">
                <div class="pic mr-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU"
                        width="60px" />
                </div>
                <div class="info">
                    <div>1 x This is an awesome book</div>
                    <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                    <div>Sent to: <strong class="font-weight-semibold"> Louis Pham  </strong>· <a href="">louis@email.com</a></div>
                </div>
                <div class="status ml-auto">
                    <div>10 minutes ago</div>
                    <div class="status text-grey">sent</div>
                </div>
            </div>
            <div class="c-item d-flex mb-4">
                <div class="pic mr-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU"
                        width="60px" />
                </div>
                <div class="info">
                    <div>1 x This is an awesome book</div>
                    <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                    <div>Sent to: <strong class="font-weight-semibold"> Louis Pham  </strong>· <a href="">louis@email.com</a></div>
                </div>
                <div class="status ml-auto">
                    <div>10 minutes ago</div>
                    <div class="status text-danger">purchased</div>
                </div>
            </div>
            <div class="c-item d-flex mb-4">
                <div class="pic mr-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROgr4jUvYblUsIC9odChEvXhgdWznOB0nkTPjzDVZejiGxnW-I6WHsSic9Wuj6Rn3depY&usqp=CAU"
                        width="60px" />
                </div>
                <div class="info">
                    <div>1 x This is an awesome book</div>
                    <div>Price: <strong>$22</strong> · <a href="">View item in Woo</a></div>
                    <div>Sent to: <strong class="font-weight-semibold"> Louis Pham  </strong>· <a href="">louis@email.com</a></div>
                </div>
                <div class="status ml-auto">
                    <div>10 minutes ago</div>
                    <div class="status text-grey">sent</div>
                </div>
            </div>
        </div>
    </div>
@endsection