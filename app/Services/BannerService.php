<?php

namespace App\Services;

use App\Http\Requests\Banner\StoreRequest;
use App\Http\Requests\Banner\UpdateRequest;
use App\Models\Banner;
use App\Services\Traits\ImageConvertable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class BannerService
{
    use ImageConvertable;

    protected $dir = 'storage/images/banners/';
    protected $width = 450;
    protected $height = 450;

    public function store(StoreRequest $request) : Model
    {
        $data = $request->safe()->except(['image']);
        $image = $request->file('image');

        $banner = Banner::create($data);

        if ($image) :
            $path = $this->dir . $image->hashName();
            $src = $this->convertSaveImage($image, $path, $this->width, $this->height);
            $banner->image()->create(['src' => $src]);
        endif;

        return $banner;
    }

    public function update(UpdateRequest $request, Banner $banner) : Model
    {
        $data = $request->safe()->except(['image']);
        $image = $request->file('image');

        $banner->update($data);

        if ($image) :
            $path = $this->dir . $image->hashName();
            $src = $this->convertSaveImage($image, $path, $this->width, $this->height);

            if ($banner->image) :
                $banner->image()->update(['src' => $src]);
                File::delete($banner->image->src);
            else : 
                $banner->image()->create(['src' => $src]);
            endif;
        endif;

        $banner->refresh();

        return $banner;
    }

    public function destroy(Banner $banner) : bool
    {
        if ($banner->image) :
            File::delete($banner->image->src);
            $banner->image()->delete();
        endif;

        return $banner->delete();
    }
}