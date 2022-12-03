<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $campaign->subject }}</title>

    <style>
        @if ($campaign->type != 'plain-text')
            body {
                padding-top: 45px !important;
            }
        @endif
        /* Style the tab */
        div.tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            @if ($campaign->type != 'plain-text')
                position: fixed;
                top: 0;
                width: 100%;
                left: 0;
            @endif
            z-index: 2;
        }

        /* Style the buttons inside the tab */
        div.tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        div.tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        div.tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
            border-bottom: none;
        }
    </style>
</head>

<body>
    @if ($campaign->type != 'plain-text')
        <div class="tab">
          <button class="tablinks active" onclick="openTab(event, 'html')">{{ trans('messages.web_view_html_tab') }}</button>
          <button class="tablinks" onclick="openTab(event, 'plain')">{{ trans('messages.web_view_plain_tab') }}</button>
        </div>

        <div id="html" class="tabcontent" style="display: block">
            {!! $campaign->getHtmlContent() !!}
        </div>
    @endif

    @if ($campaign->type != 'plain-text')
        <div id="plain" class="tabcontent">
    @endif
        {!! $campaign->plain !!}
    @if ($campaign->type != 'plain-text')
        </div>
    @endif

    <script>
    function openTab(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    </script>
</body>

</html>

