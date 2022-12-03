@extends('layouts.core.backend')

@section('head')
<script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/echarts/dark.js') }}"></script>
@endsection

@section('content')
<style>
h1 {
    opacity: 0.5;
}

/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}

input {
    background: transparent;
    border: none;
    border-bottom: 1px solid #000000;
}

input[type=checkbox] {
    width: 1rem;
    height: 1rem;
}

.input-form {
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 5rem;
    min-width: 3rem;
}

.title {
    display: flex;
    align-items: center;
    min-width: 250px;
    font-size: 20px;
}

.input-wrap {
    display: flex;
    align-items: center;
    gap: 2rem;
}

label {
    font-size: 20px;
}

li {
    font-size: 20px;
}
</style>

<h1>WarmUp</h1>

<div class="tab">
    <button class="tablinks" onclick="openWeek(event, 'success')">Key to Success</button>
    <button class="tablinks" onclick="openWeek(event, 'week1')">Week1</button>
    <button class="tablinks" onclick="openWeek(event, 'week2')">Week2</button>
    <button class="tablinks" onclick="openWeek(event, 'week3')">Week3</button>
    <button class="tablinks" onclick="openWeek(event, 'week4')">Week4</button>
</div>

<div id="success" class="tabcontent">
    <ul>
        <li>Use domain has more than 3 months age or more. As the domain more older, more better</li>
        <li>Use main business domain is not a good idea for mass emailing or cold emailing</li>
        <li>New domains are under blacklisted for a month, do not send emails</li>
        <li>During weeks 1-2 send to your most active subscribes-those who have open clicked in the past 30 days</li>
        <li>During weeks 3-4 you can expand to subscribers who have opened/clicked in the past 50 days</li>
        <li>During the first 6 weeks do NOT send to subscribers who have not opened or clicked in the past 90 days</li>
        <li>If warming above 10 million subscribers, consider adding another IP</li>
    </ul>
</div>

<div id="week1" class="tabcontent">
    <h1>Week 1</h1>
    @foreach($datas_week1 as $data)
    <form class="input-form" action="{{ url('admin/warmup/week1/'.$data->id) }}" method="POST">
        @csrf
        <div class="input-wrap">
            <span class="title">Day {{$data->id}}</span>
            <input type="number" step="1" name="count" value="{{$data->count}}">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    @endforeach
</div>

<div id="week2" class="tabcontent">
    <h1>Week 2</h1>
    @foreach($datas_week2 as $data)
    <form class="input-form" action="{{ url('admin/warmup/week2/'.$data->id) }}" method="POST">
        @csrf
        <div class="input-wrap">
            <span class="title">Day {{$data->id}}</span>
            <input type="number" step="1" name="count" value="{{$data->count}}">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    @endforeach
</div>

<div id="week3" class="tabcontent">
    <h1>Week 3</h1>
    @foreach($datas_week3 as $data)
    <form class="input-form" action="{{ url('admin/warmup/week3/'.$data->id) }}" method="POST">
        @csrf
        <div class="input-wrap">
            <span class="title">Day {{$data->id}}</span>
            <input type="number" step="1" name="count" value="{{$data->count}}">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    @endforeach
</div>

<div id="week4" class="tabcontent">
    <h1>Week 4</h1>
    @foreach($datas_week4 as $data)
    <form class="input-form" action="{{ url('admin/warmup/week4/'.$data->id) }}" method="POST">
        @csrf
        <div class="input-wrap">
            <span class="title">Day {{$data->id}}</span>
            <input type="number" step="1" name="count" value="{{$data->count}}">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    @endforeach
</div>

<script>
function openWeek(evt, content) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(content).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
@endsection