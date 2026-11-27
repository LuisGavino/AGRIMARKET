@props(['label', 'image', 'href' => '#'])
<a href="{{ $href }}" class="category-card">
    <img src="{{ asset('images/categories/' . $image) }}" alt="{{ $label }}" class="category-card__img">
    <span>{{ $label }}</span>
</a>
