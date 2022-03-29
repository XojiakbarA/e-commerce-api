<?php

namespace App\Http\Filters;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Shop;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends AbstractFilter
{
    public const ID = 'id';
    public const TITLE = 'title';
    public const CAT_ID = 'cat_id';
    public const SUB_CAT_ID = 'sub_cat_id';
    public const BRAND_ID = 'brand_id';
    public const RATING = 'rating';
    public const STOCK = 'stock';
    public const SORT_BY = 'sort_by';
    public const PRICE = 'price';
    public const SALE_PRICE = 'sale_price';
    public const PRICE_MIN = 'price_min';
    public const PRICE_MAX = 'price_max';
    public const CATEGORY_TITLE = 'category_title';
    public const BRAND_TITLE = 'brand_title';
    public const SHOP_TITLE = 'shop_title';

    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'Id'],
            self::TITLE => [$this, 'title'],
            self::CAT_ID => [$this, 'catId'],
            self::SUB_CAT_ID => [$this, 'subCatId'],
            self::BRAND_ID => [$this, 'brandId'],
            self::RATING => [$this, 'rating'],
            self::STOCK => [$this, 'stock'],
            self::SORT_BY => [$this, 'sortBy'],
            self::PRICE => [$this, 'price'],
            self::SALE_PRICE => [$this, 'salePrice'],
            self::PRICE_MIN => [$this, 'priceMin'],
            self::PRICE_MAX => [$this, 'priceMax'],
            self::CATEGORY_TITLE => [$this, 'categoryTitle'],
            self::BRAND_TITLE => [$this, 'brandTitle'],
            self::SHOP_TITLE => [$this, 'shopTitle'],
        ];
    }

    public function Id(Builder $builder, $value)
    {
        $builder->whereIn(self::ID, $value);
    }

    public function title(Builder $builder, $value)
    {
        $builder->where(self::TITLE, 'like', '%' . $value . '%');
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

        $builder->whereIn(self::RATING, $arr);
    }

    public function stock(Builder $builder, $value)
    {
        $builder->where(self::STOCK, $value);
    }

    public function sortBy(Builder $builder, $value)
    {
        switch ($value[0]) {
            case self::CATEGORY_TITLE:
                $category = Category::select('title')->whereColumn('id', 'sub_categories.category_id')->getQuery();
                $builder->orderBy(SubCategory::selectSub($category, 'title')
                        ->whereColumn('sub_categories.id', 'products.sub_category_id'), $value[1]);
                break;
            case self::BRAND_TITLE:
                $builder->orderBy(Brand::select('title')
                        ->whereColumn('brands.id', 'products.brand_id'), $value[1]);
                break;
            case self::SHOP_TITLE:
                $builder->orderBy(Shop::select('title')
                        ->whereColumn('shops.id', 'products.shop_id'), $value[1]);
                break;
            default:
                $builder->orderBy($value[0], $value[1]);
                break;
        }
    }

    public function price(Builder $builder, $value)
    {
        $builder->where(self::PRICE, $value);
    }

    public function salePrice(Builder $builder, $value)
    {
        $builder->where(self::SALE_PRICE, $value);
    }

    public function priceMin(Builder $builder, $value)
    {
        $builder->where(self::PRICE, '>', $value);
    }

    public function priceMax(Builder $builder, $value)
    {
        $builder->where(self::PRICE, '<', $value);
    }

    public function categoryTitle(Builder $builder, $value)
    {
        $builder->whereRelation('subCategory.category', 'title', 'like', '%' . $value . '%');
    }

    public function brandTitle(Builder $builder, $value)
    {
        $builder->whereRelation('brand', 'title', 'like', '%' . $value . '%');
    }

    public function shopTitle(Builder $builder, $value)
    {
        $builder->whereRelation('shop', 'title', 'like', '%' . $value . '%');
    }
}
