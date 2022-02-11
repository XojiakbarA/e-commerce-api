<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends AbstractFilter
{
    public const ID = 'id';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const EMAIL = 'email';
    public const PHONE = 'phone';
    public const BIRTH_DATE = 'birth_date';
    public const ROLE = 'role';

    public const SORT_BY = 'sort_by';

    protected function getCallbacks(): array
    {
        return [
            self::FIRST_NAME => [$this, 'firstName'],
            self::LAST_NAME => [$this, 'last_name'],
            self::EMAIL => [$this, 'email'],
            self::PHONE => [$this, 'phone'],
            self::BIRTH_DATE => [$this, 'birthDate'],
            self::ROLE => [$this, 'role'],
            self::SORT_BY => [$this, 'sortBy']
        ];
    }

    public function sortBy(Builder $builder, $value)
    {
        switch ($value[0]) {
            case self::ID:
                $builder->orderBy($value[0], $value[1]);
                break;
            case self::FIRST_NAME:
                $builder->orderBy($value[0], $value[1]);
                break;
            case self::LAST_NAME:
                    $builder->orderBy($value[0], $value[1]);
                    break;
            case self::EMAIL:
                $builder->orderBy($value[0], $value[1]);
                break;
            case self::PHONE:
                $builder->orderBy($value[0], $value[1]);
                break;
            case self::BIRTH_DATE:
                $builder->orderBy($value[0], $value[1]);
                break;
            case self::ROLE:
                $builder->orderBy($value[0], $value[1]);
                break;
            default:
                $builder;
                break;
        }
    }

    public function firstName(Builder $builder, $value)
    {
        $builder->where(self::FIRST_NAME, 'like', '%' . $value . '%');
    }

    public function lastName(Builder $builder, $value)
    {
        $builder->where(self::LAST_NAME, 'like', '%' . $value . '%');
    }

    public function email(Builder $builder, $value)
    {
        $builder->where(self::EMAIL, 'like', '%' . $value . '%');
    }

    public function phone(Builder $builder, $value)
    {
        $builder->where(self::PHONE, 'like', '%' . $value . '%');
    }

    public function birthDate(Builder $builder, $value)
    {
        $builder->where(self::BIRTH_DATE, 'like', '%' . $value . '%');
    }

    public function role(Builder $builder, $value)
    {
        $builder->where(self::ROLE, 'like', '%' . $value . '%');
    }
}