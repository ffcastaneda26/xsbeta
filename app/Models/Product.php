<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'stock',
        'images',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    // protected function images(): Attribute
    // {
    //     return Attribute::make(
    //         get: function ($value) {
    //             // El atributo 'images' ya está deserializado como un array por el 'cast' JSON
    //             // Verificamos si es un array válido y no está vacío
    //             if (is_array($value)) {
    //                 // Mapeamos el array para generar la URL completa de cada imagen
    //                 return array_map(function ($imageName) {
    //                     return asset('storage/products/' . $imageName);
    //                 }, $value);
    //             }
    //             return [];
    //         }
    //     );
    // }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'category_product', 'product_id', 'product_category_id');
    }


}
