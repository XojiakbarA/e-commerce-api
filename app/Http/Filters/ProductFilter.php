<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends AbstractFilter
{
    public const TITLE = 'title';
    public const CATEGORY_ID = 'category_id';
    public const BRAND_ID = 'brand_id';
    public const RATING = 'rating';
    public const AVAIL = 'avail';

    protected function getCallbacks(): array
    {
        return [
            self::TITLE => [$this, 'title'],
            self::CATEGORY_ID => [$this, 'categoryId'],
            self::BRAND_ID => [$this, 'brandId'],
            self::RATING => [$this, 'rating'],
            self::AVAIL => [$this, 'avail']
        ];
    }

    public function title(Builder $builder, $value)
    {
        $builder->where('title', 'like', '%' . $value . '%');
    }

    public function categoryId(Builder $builder, $value)
    {
        $builder->where('category_id', $value);
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
}
