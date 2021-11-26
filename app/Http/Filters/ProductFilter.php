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

    protected function getCallbacks(): array
    {
        return [
            self::TITLE => [$this, 'title'],
            self::CAT_ID => [$this, 'catId'],
            self::SUB_CAT_ID => [$this, 'subCatId'],
            self::BRAND_ID => [$this, 'brandId'],
            self::RATING => [$this, 'rating'],
            self::AVAIL => [$this, 'avail'],
            self::SORT => [$this, 'sort']
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
        $builder->where('brand_id', $value);
    }

    public function rating(Builder $builder, $value)
    {
        $builder->where('rating', $value);
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
        endif;
    }
}
