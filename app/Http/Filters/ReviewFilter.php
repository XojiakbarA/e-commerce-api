<?php

namespace App\Http\Filters;

use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ReviewFilter extends AbstractFilter
{
    public const ID = 'id';
    public const USER_NAME = 'user_name';
    public const RATING = 'rating';
    public const TEXT = 'text';
    public const PRODUCT_TITLE = 'product_title';
    public const PUBLISHED = 'published';

    public const SORT_BY = 'sort_by';

    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
            self::SORT_BY => [$this, 'sortBy'],
            self::USER_NAME => [$this, 'userName'],
            self::RATING => [$this, 'rating'],
            self::TEXT => [$this, 'text'],
            self::PRODUCT_TITLE => [$this, 'productTitle'],
            self::PUBLISHED => [$this, 'published']
        ];
    }

    public function sortBy(Builder $builder, $value)
    {
        switch ($value[0]) {
            case self::ID:
                $builder->orderBy($value[0], $value[1]);
                break;
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

    public function id(Builder $builder, $value)
    {
        $builder->where(self::ID, $value);
    }

    public function userName(Builder $builder, $value)
    {
        $builder->whereRelation('user', 'first_name', 'like', '%' . $value . '%');
    }

    public function rating(Builder $builder, $value)
    {
        $builder->where(self::RATING, $value);
    }

    public function text(Builder $builder, $value)
    {
        $builder->where(self::TEXT, 'like', '%' . $value . '%');
    }

    public function productTitle(Builder $builder, $value)
    {
        $builder->whereRelation('product', 'title', 'like', '%' . $value . '%');
    }

    public function published(Builder $builder, $value)
    {
        $builder->where(self::PUBLISHED, $value);
    }
}