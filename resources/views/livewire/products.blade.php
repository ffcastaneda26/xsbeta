<div class="container mx-auto p-4">
    <header class="flex justify-between items-center mb-6">
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
        </div>
        <div class="text-3xl font-bold text-center">
            <h1>Listado de Productos</h1>
        </div>
        <div>
            <input wire:model.live="searchQuery" type="text" placeholder="Buscar productos..." class="border p-2 rounded-lg">
        </div>
    </header>

    <nav class="flex justify-center items-center gap-4 mb-8">
        <a href="#" wire:click.prevent="filterByCategory('all')" class="p-2 border rounded-full {{ $selectedCategory == 'all' ? 'bg-blue-500 text-white' : '' }}">Todas</a>
        @foreach ($categories->take(6) as $category)
            <a href="#" wire:click.prevent="filterByCategory({{ $category->id }})" class="p-2 border rounded-full {{ $selectedCategory == $category->name ? 'bg-blue-500 text-white' : '' }}">
                {{ $category->name }}
            </a>
        @endforeach

        @if ($categories->count() > 6)
            <div class="relative group">
                <button class="p-2 border rounded-full">Más Categorías</button>
                <div class="absolute hidden group-hover:block bg-white border rounded-lg shadow-lg mt-2 z-10">
                    @foreach ($categories->skip(6) as $category)
                        <a href="#" wire:click.prevent="filterByCategory({{ $category->id }})" class="block p-2 hover:bg-gray-100">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </nav>

    <hr class="mb-8">

    <main class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 overflow-hidden flex items-center justify-center">
                    @if (!empty($product->images) && is_array($product->images))
                        <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/generico.jpg') }}" alt="Imagen genérica" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-xl mb-2">{{ $product->name }}</h3>
                    <p class="text-blue-600 font-semibold text-lg">
                        ${{ number_format($product->price, 2, '.', ',') }}
                    </p>
                </div>
            </div>
        @empty
            <p>No hay productos disponibles en esta categoría.</p>
        @endforelse
    </main>
</div>
