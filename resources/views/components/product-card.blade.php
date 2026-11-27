@props(['name', 'price', 'image' => 'product-green-apple.png', 'rating' => 4])
<article class="product-card">
    <div class="product-card__quick">
        <button type="button" aria-label="Add to wishlist"><x-icon name="heart" :size="16" /></button>
        <button type="button" aria-label="Quick view"><x-icon name="eye" :size="16" /></button>
    </div>

    <img src="{{ asset('images/products/' . $image) }}" alt="{{ $name }}" class="product-card__img">

    <div class="product-card__body">
        <div>
            <div class="product-card__name">{{ $name }}</div>
            <div class="product-card__price">{{ $price }}</div>
            <div class="product-card__stars" aria-label="{{ $rating }} out of 5">
                {!! str_repeat('★', $rating) !!}<span class="off">{!! str_repeat('★', 5 - $rating) !!}</span>
            </div>
        </div>
        <button type="button" class="product-card__bag" aria-label="Add to cart"><x-icon name="bag" :size="18" /></button>
    </div>
</article>
