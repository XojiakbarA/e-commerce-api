<?php

namespace App\Http\Filters;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ReviewFilter extends AbstractFilter
{
    public const USER_NAME = 'user_name';
    public const PRODUCT_TITLE = 'product_title';

    public const SORT_BY = 'sort_by';

    protected function getCallbacks(): array
    {
        return [
            self::SORT_BY => [$this, 'sortBy']
        ];
    }

    public function sortBy(Builder $builder, $value)
    {
        switch ($value[0]) {
            case self::USER_NAME:
                $builder->orderBy(User::select('first_name')
                        ->whereColumn('users.id', 'reviews.user_id'), $value[1]);
                break;
            case self::PRODUCT_TITLE:
                $builder->orderBy(Product::select('title')
                        ->whereColumn('products.id', 'reviews.product_id'), $value[1]);
                break;
            default:
                $builder->orderBy($value[0], $value[1]);
                break;
        }
    }
}