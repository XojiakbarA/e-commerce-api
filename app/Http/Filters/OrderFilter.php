<?php

namespace App\Http\Filters;

use App\Models\District;
use App\Models\OrderProduct;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PHONE = 'phone';
    public const REGION = 'region';
    public const DISTRICT = 'district';
    public const STREET = 'street';
    public const HOME = 'home';
    public const ORDER_PRODUCTS = 'order_products';
    public const TOTAL = 'total';

    public const SORT_BY = 'sort_by';

    public function getCallbacks(): array
    {
        return [
            self::NAME => [$this, 'name'],
            self::EMAIL => [$this, 'email'],
            self::PHONE => [$this, 'phone'],
            self::REGION => [$this, 'region'],
            self::DISTRICT => [$this, 'district'],
            self::STREET => [$this, 'street'],
            self::HOME => [$this, 'home'],
            self::ORDER_PRODUCTS => [$this, 'orderProducts'],
            self::TOTAL => [$this, 'total'],

            self::SORT_BY => [$this, 'sortBy']
        ];
    }

    public function name(Builder $builder, $value)
    {
        $builder->where(self::NAME, 'like', '%' . $value . '%');
    }

    public function email(Builder $builder, $value)
    {
        $builder->where(self::EMAIL, 'like', '%' . $value . '%');
    }

    public function phone(Builder $builder, $value)
    {
        $builder->where(self::PHONE, 'like', '%' . $value . '%');
    }

    public function region(Builder $builder, $value)
    {
        $builder->whereRelation('district.region', 'name', 'like', '%' . $value . '%');
    }

    public function district(Builder $builder, $value)
    {
        $builder->whereRelation(self::DISTRICT, 'name', 'like', '%' . $value . '%');
    }

    public function street(Builder $builder, $value)
    {
        $builder->where(self::STREET, 'like', '%' . $value . '%');
    }

    public function home(Builder $builder, $value)
    {
        $builder->where(self::HOME, 'like', '%' . $value . '%');
    }

    public function sortBy(Builder $builder, $value)
    {
        switch ($value[0]) {
            case self::REGION:
                $region = Region::select('name')->whereColumn('id', 'districts.region_id')->getQuery();
                $builder->orderBy(District::selectSub($region, 'name')
                        ->whereColumn('districts.id', 'orders.district_id'), $value[1]);
                break;
            case self::DISTRICT:
                $builder->orderBy(District::select('name')
                ->whereColumn('districts.id', 'orders.district_id'), $value[1]);
                break;
            case self::ORDER_PRODUCTS:
                $builder->withCount('orderProducts')->orderBy('order_products_count', $value[1]);
                break;
            default:
                $builder->orderBy($value[0], $value[1]);
                break;
        }
    }
}