<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "updating" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updating(Product $product)
    {
        // Solo ejecuta la lógica si el atributo 'images' ha cambiado
        if ($product->isDirty('images')) {
            // Obtiene el array de imágenes antes de la actualización
            $originalImages = $product->getOriginal('images');

            // Obtiene el array de imágenes después de la actualización (el nuevo estado)
            $updatedImages = $product->images;

            // Encuentra los nombres de archivo que están en el array original
            // pero no en el array actualizado
            $imagesToDelete = array_diff($originalImages, $updatedImages);

            // Elimina cada archivo del disco
            foreach ($imagesToDelete as $imagePath) {
                // Asegúrate de que el archivo existe antes de intentar eliminarlo
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }
    }

    /**
     * También puedes manejar la eliminación completa de un registro.
     * Esto eliminará todas las imágenes cuando se borre el producto.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        if (is_array($product->images)) {
            foreach ($product->images as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }
    }
}
