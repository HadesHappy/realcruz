<div>
    <input
        type="password"
        id="{{ $name }}"
        value="{{ isset($value) ? $value : "" }}"
        autocomplete="new-password"
        type="text"
        name="{{ $name }}"
        class="form-control{{ $classes }} {{ isset($eye) && $eye == true ? "has-eye" : "" }}"
        {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }}
    >
    @if (isset($eye) && $eye == true)
        <span class="material-symbols-rounded btn-view-password">visibility</span>
    @endif
</div>
