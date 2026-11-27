@props(['title', 'href' => '#'])
<div class="section-heading">
    <h2>{{ $title }}</h2>
    <a href="{{ $href }}" class="section-heading__link">
        View All <x-icon name="arrow-right" :size="16" />
    </a>
</div>
