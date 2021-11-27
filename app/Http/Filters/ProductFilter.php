<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends AbstractFilter
{
    public const TITLE = 'title';
    public const CAT_ID = 'cat_id';
    public const SUB_CAT_ID = 'sub_cat_id';
    public const BRAND_ID = 'brand_id';
    public const RATING = 'rating';
    public const AVAIL = 'avail';
    public const SORT = 'sort';
    public const PRICE_MIN = 'price_min';
    public const PRICE_MAX = 'price_max';

    protected function getCallbacks(): array
    {
        return [
            self::TITLE => [$this, 'title'],
            self::CAT_ID => [$this, 'catId'],
            self::SUB_CAT_ID => [$this, 'subCatId'],
            self::BRAND_ID => [$this, 'brandId'],
            self::RATING => [$this, 'rating'],
            self::AVAIL => [$this, 'avail'],
            self::SORT => [$this, 'sort'],
            self::PRICE_MIN => [$this, 'priceMin'],
            self::PRICE_MAX => [$this, 'priceMax']
        ];
    }

    public function title(Builder $builder, $value)
    {
        if ($value == 'all') :
            return;
        endif;
        $builder->where('title', 'like', '%' . $value . '%');
    }

    public function catId(Builder $builder, $value)
    {
        $builder->whereRelation('subCategory.category', 'id', $value);
    }

    public function subCatId(Builder $builder, $value)
    {
        $builder->where('sub_category_id', $value);
    }

    public function brandId(Builder $builder, $value)
    {
        $arr = explode(',', $value);
        $res = array_filter($arr, function ($id) { return $id > 0; });

        if (empty($res)) return;

        $builder->whereIn('brand_id', $arr);

    }

    public function rating(Builder $builder, $value)
    {
        $arr = explode(',', $value);
        $res = array_filter($arr, function ($id) { return $id > 0; });

        if (empty($res)) return;

        $builder->whereIn('rating', $arr);
    }

    public function avail(Builder $builder, $value)
    {
        if ($value) :
            $builder->where('avail', true);
        endif;

        $builder->get();
    }

    public function sort(Builder $builder, $value)
    {
        if ($value == 'new') :
            $builder->latest();
        elseif ($value == 'expensive') :
            $builder->orderBy('price', 'desc');
        elseif ($value == 'cheap') :
            $builder->orderBy('price');
        endif;
    }

    public function priceMin(Builder $builder, $value)
    {
        $builder->where('price', '>', $value);
    }

    public function priceMax(Builder $builder, $value)
    {
        $builder->where('price', '<', $value);
    }
}
