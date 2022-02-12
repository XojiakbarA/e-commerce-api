<?php

namespace App\Http\Filters;

use App\Models\District;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ShopFilter extends AbstractFilter
{
    public const TITLE = 'title';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const REGION = 'region';
    public const DISTRICT = 'district';
    public const STREET = 'street';
    public const HOME = 'home';
    public const PHONE = 'phone';

    public const SORT_BY = 'sort_by';

    protected function getCallbacks(): array
    {
        return [
            self::TITLE => [$this, 'title'],
            self::FIRST_NAME => [$this, 'firstName'],
            self::LAST_NAME => [$this, 'last_name'],
            self::REGION => [$this, 'region'],
            self::DISTRICT => [$this, 'district'],
            self::STREET => [$this, 'street'],
            self::HOME => [$this, 'home'],
            self::PHONE => [$this, 'phone'],
            self::SORT_BY => [$this, 'sortBy']
        ];
    }

    public function sortBy(Builder $builder, $value)
    {
        switch ($value[0]) {
            case self::FIRST_NAME:
                $builder->orderBy(User::select(self::FIRST_NAME)
                ->whereColumn('users.id', 'shops.user_id'), $value[1]);
                break;
            case self::LAST_NAME:
                $builder->orderBy(User::select(self::LAST_NAME)
                ->whereColumn('users.id', 'shops.user_id'), $value[1]);
                break;
            case self::REGION:
                $region = Region::select('name')->whereColumn('id', 'districts.region_id')->getQuery();
                $builder->orderBy(District::selectSub($region, 'name')
                        ->whereColumn('districts.id', 'shops.district_id'), $value[1]);
                break;
            case self::DISTRICT:
                $builder->orderBy(District::select('name')
                ->whereColumn('districts.id', 'shops.district_id'), $value[1]);
                break;
            default:
                $builder->orderBy($value[0], $value[1]);
                break;
        }
    }

    public function title(Builder $builder, $value)
    {
        $builder->where(self::TITLE, 'like', '%' . $value . '%');
    }

    public function firstName(Builder $builder, $value)
    {
        $builder->whereRelation('user', self::FIRST_NAME, 'like', '%' . $value . '%');
    }

    public function lastName(Builder $builder, $value)
    {
        $builder->whereRelation('user', self::LAST_NAME, 'like', '%' . $value . '%');
    }

    public function region(Builder $builder, $value)
    {
        $builder->whereRelation( 'district.region', 'name', 'like', '%' . $value . '%');
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

    public function phone(Builder $builder, $value)
    {
        $builder->where(self::PHONE, 'like', '%' . $value . '%');
    }
}