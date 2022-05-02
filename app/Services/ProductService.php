<?php

namespace App\Services;

use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use App\Services\Traits\ImageConvertable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ProductService
{
    use ImageConvertable;

    protected $dir = 'storage/images/products/';
    protected $mainDir = 'storage/images/products/main/';
    protected $width = 500;
    protected $height = 625;
    protected $mainWidth = 300;
    protected $mainHeight = 300;

    public function store(StoreRequest $request, User $user) : Model
    {
        $data = $request->safe()->except('images');
        $images = $request->file('images');

        $product = $user->shop->products()->create($data);

        if ($images) :
            $imageSRCs = $this->makeImages($images, true);
            $product->images()->createMany($imageSRCs);
        endif;

        return $product;
    }

    public function update(UpdateRequest $request, Product $product) : Model
    {
        $data = $request->safe()->except('images');
        $images = $request->file('images');

        $product->update($data);

        if ($images) :
            $needMainImage = !$product->main_image;
            $imageSRCs = $this->makeImages($images, $needMainImage);
            $product->images()->createMany($imageSRCs);
        endif;

        $product->refresh();

        return $product;
    }

    public function destroy(Product $product) : bool
    {
        if ($product->images) :
            File::delete($product->main_image);

            foreach ($product->images as $image) :
                File::delete($image->src);
            endforeach;

            $product->images()->delete();
        endif;

        return $product->delete();
    }

    public function imageDestroy(Product $product, Image $image) : bool
    {
        if ($image->main) :
            $imageUrlArray = explode('/', $image->src);
            $imageName = end($imageUrlArray);

            File::delete($image->src);
            File::delete($this->mainDir . $imageName);

            $deleted = $image->delete();

            $product->refresh();

            $firstImage = $product->images()->first();

            if ($firstImage) :
                $imageFile = $this->createFileObject($firstImage->src);
                $this->makeMainImage($imageFile);

                $firstImage->update(['main' => 1]);

                File::delete($imageFile);
            endif;

        else :
            File::delete($image->src);
            $deleted = $image->delete();
        endif;

        return $deleted;
    }

    protected function makeMainImage(UploadedFile $image) : string
    {
        $path = $this->mainDir . $image->hashName();
        return $this->convertSaveImage($image, $path, $this->mainWidth, $this->mainHeight);
    }

    protected function makeImages(array $images, bool $needMainImage = false) : array
    {
        $imageSRCs = [];

        if ($needMainImage) :
            $this->makeMainImage($images[0]);
        endif;

        foreach ($images as $key => $image) :
            $main = 0;

            if ($key === 0) :
                $main = $needMainImage ? 1 : 0;
            endif;

            $path = $this->dir . $image->hashName();

            $src = $this->convertSaveImage($image, $path, $this->width, $this->height);

            array_push($imageSRCs, [ 'src' => $src, 'main' => $main]);
        endforeach;

        return $imageSRCs;
    }

    protected function createFileObject(string $url) : UploadedFile
    {
        $path_parts = pathinfo($url);

        $newPath = $path_parts['dirname'] . '/tmp-files/';
        if(!is_dir ($newPath)){
            mkdir($newPath, 0777);
        }

        $newUrl = $newPath . $path_parts['basename'];
        copy($url, $newUrl);
        $imgInfo = getimagesize($newUrl);

        $file = new UploadedFile(
            $newUrl,
            $path_parts['basename'],
            $imgInfo['mime'],
            filesize($url),
            true,
            TRUE
        );

        return $file;
    }
}