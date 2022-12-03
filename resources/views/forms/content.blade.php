{!! $content !!}

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        var content_height = document.body.scrollHeight;
        parent.postMessage(content_height, '*');
    });
</script>