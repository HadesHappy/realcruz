@if ($result['status'] == 'error')
    <div class="alert alert-danger">
        {{ $result['message'] }}
    </div>
@elseif ($result['status'] == 'sent')
    @php
        $status = $result['code'] < 200 ? 'info' : ($result['code'] < 300 ? 'success' : ($result['code'] < 400 ? 'info' : ($result['code'] < 600 ? 'danger' : '')));
    @endphp
    <div class="mb-4">
        <span class="badge rounded badge-lg py-1 badge-{{ $status }}">{{ $result['code'] }}</span>
        <span class=""><code class="text-primary">{{ $result['message'] }}</code></span>
    </div>
@endif