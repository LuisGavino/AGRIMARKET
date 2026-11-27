@php
    $popular = [
        ['label' => 'Fresh Fruit',      'image' => 'fresh-fruit.png'],
        ['label' => 'Fresh Vegetables', 'image' => 'fresh-vegetables.png'],
        ['label' => 'Meat & Fish',      'image' => 'meat-fish.png'],
        ['label' => 'Livestock',        'image' => 'livestock.png'],
        ['label' => 'Seeds',            'image' => 'seeds.png'],
        ['label' => 'Poultry',          'image' => 'poultry.png'],
        ['label' => 'Herbs & Spices',   'image' => 'herbs-spices.png'],
    ];

    $services = [
        ['label' => 'Veterinary Care',              'image' => 'service-veterinary.png'],
        ['label' => 'Equipment & Machinery Rental', 'image' => 'service-equipment.png'],
        ['label' => 'Pest & Disease Control',       'image' => 'service-pest-control.png'],
        ['label' => 'Crop Production & Farm Labor', 'image' => 'service-crop-labor.png'],
        ['label' => 'Transportation & Hauling',     'image' => 'service-transport.png'],
    ];
@endphp

<div class="app-content">
    <x-section-heading title="Popular Categories" />
    <div class="category-grid">
        @foreach ($popular as $item)
            <x-category-card :label="$item['label']" :image="$item['image']" />
        @endforeach
    </div>

    <x-section-heading title="Service Provider Category" />
    <div class="category-grid">
        @foreach ($services as $item)
            <x-category-card :label="$item['label']" :image="$item['image']" />
        @endforeach
    </div>
</div>
