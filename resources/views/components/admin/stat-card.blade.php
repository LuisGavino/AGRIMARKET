@props(['title', 'value', 'delta' => null, 'caption' => null])

<div {{ $attributes->merge(['class' => 'card stat']) }}>
    <div class="top">
        <h3>{{ $title }}</h3>
        <x-admin.icon name="dots" :size="24" class="dots-v" />
    </div>
    <div class="num">
        {{ $value }}
        @if ($delta)
            <small>
                @if (str_starts_with($delta, '↑'))<x-admin.icon name="arrow-up" :size="13" :stroke="2.4" />{{ ltrim($delta, '↑') }}@else{{ $delta }}@endif
            </small>
        @endif
    </div>
    @if ($caption)
        <div class="cap">{{ $caption }}</div>
    @endif
</div>
