<?php

namespace App\Services;

use App\Http\Requests\Shop\StoreRequest;
use App\Http\Requests\Shop\UpdateRequest;
use App\Models\Image;
use App\Models\Shop;
use App\Models\User;
use App\Services\Traits\ImageConvertable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ShopService
{
    use ImageConvertable;

    protected $dir = 'storage/images/shops/';
    protected $width = [
        'bg_big' => 1152,
        'bg_small' => 750,
        'avatar' => 200,
    ];
    protected $height = [
        'bg_big' => 330,
        'bg_small' => 400,
        'avatar' => 200,
    ];

    public function store(StoreRequest $request, User $user)
    {
        $data = $request->safe()->except(['bg_image', 'av_image']);
        $bg_image = $request->file('bg_image');
        $av_image = $request->file('av_image');

        $shop = $user->shop()->create($data);

        $imageSRCs = [];

        if ($bg_image) :
            $src = $this->makeImage($bg_image, 'bg_big');
            array_push($imageSRCs, ['src' => $src, 'status' => 'bg_big']);

            $src = $this->makeImage($bg_image, 'bg_small');
            array_push($imageSRCs, ['src' => $src, 'status' => 'bg_small']);
        endif;

        if ($av_image) :
            $src = $this->makeImage($av_image, 'avatar');
            array_push($imageSRCs, ['src' => $src, 'status' => 'avatar']);
        endif;

        $shop->images()->createMany($imageSRCs);

        $user->update(['role' => 'vendor']);

        return $shop;
    }

    public function update(UpdateRequest $request, Shop $shop)
    {
        $data = $request->safe()->except(['bg_image', 'av_image']);
        $bg_image = $request->file('bg_image');
        $av_image = $request->file('av_image');

        $shop->update($data);

        if ($bg_image) :
            $srcBig = $this->makeImage($bg_image, 'bg_big');
            $srcSmall = $this->makeImage($bg_image, 'bg_small');
            
            if ($shop->BgImageBig) :
                File::delete($shop->BgImageBig->src);
                File::delete($shop->BgImageSmall->src);

                $shop->bgImageBig()->update(['src' => $srcBig, 'status' => 'bg_big']);
                $shop->bgImageSmall()->update(['src' => $srcSmall, 'status' => 'bg_small']);
            else :
                $shop->BgImageBig()->create(['src' => $srcBig, 'status' => 'bg_big']);
                $shop->BgImageSmall()->create(['src' => $srcSmall, 'status' => 'bg_small']);
            endif;
        endif;

        if ($av_image) :
            $src = $this->makeImage($av_image, 'avatar');

            if ($shop->AvImage) :
                File::delete($shop->AvImage->src);

                $shop->AvImage()->update(['src' => $src, 'status' => 'avatar']);
            else :
                $shop->AvImage()->create(['src' => $src, 'status' => 'avatar']);
            endif;
        endif;

        $shop->refresh();

        return $shop;
    }

    public function destroy(Shop $shop)
    {
        if ($shop->images) :
            foreach ($shop->images as $image) :
                File::delete($image->src);
            endforeach;

            $shop->images()->delete();
        endif;

        return $shop->delete();
    }

    public function imageDestroy(Shop $shop, Image $image)
    {
        if ($image->status === 'bg_big' || $image->status === 'bg_small') :
            File::delete($shop->BgImageBig->src);
            File::delete($shop->BgImageSmall->src);

            $deleted = $shop->BgImageBig()->delete();
            $deleted = $shop->BgImageSmall()->delete();
        else :
            File::delete($image->src);
            $deleted = $shop->AvImage()->delete();
        endif;

        return $deleted;
    }

    protected function makeImage(UploadedFile $image, string $status) : string
    {
        $path = $this->dir . $status . $image->hashName();
        return $this->convertSaveImage($image, $path, $this->width[$status], $this->height[$status]);
    }
}