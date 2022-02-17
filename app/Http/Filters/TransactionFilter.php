<?php

namespace App\Http\Filters;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class TransactionFilter extends AbstractFilter
{
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PHONE = 'phone';
    public const PAY_MODE = 'pay_mode';
    public const STATUS = 'status';
    public const TOTAL = 'total';
    public const ORDER = 'order';

    public const SORT_BY = 'sort_by';

    public function getCallbacks(): array
    {
        return [
            self::NAME => [$this, 'name'],
            self::EMAIL => [$this, 'email'],
            self::PHONE => [$this, 'phone'],
            self::PAY_MODE => [$this, 'payMode'],
            self::STATUS => [$this, 'status'],

            self::SORT_BY => [$this, 'sortBy']
        ];
    }

    public function name(Builder $builder, $value)
    {
        $builder->whereRelation(self::ORDER, self::NAME, 'like', '%' . $value . '%');
    }

    public function email(Builder $builder, $value)
    {
        $builder->whereRelation(self::ORDER, self::EMAIL, 'like', '%' . $value . '%');
    }

    public function phone(Builder $builder, $value)
    {
        $builder->whereRelation(self::ORDER, self::PHONE, 'like', '%' . $value . '%');
    }

    public function payMode(Builder $builder, $value)
    {
        $builder->where(self::PAY_MODE, 'like', '%' . $value . '%');
    }

    public function status(Builder $builder, $value)
    {
        $builder->where(self::STATUS, 'like', '%' . $value . '%');
    }

    public function sortBy(Builder $builder, $value)
    {
        switch ($value[0]) {
            case self::NAME:
                $builder->orderBy(
                    Order::select('name')->whereColumn('orders.id', 'transactions.order_id'),
                    $value[1]
                );
                break;
            case self::EMAIL:
                $builder->orderBy(
                    Order::select('email')->whereColumn('orders.id', 'transactions.order_id'),
                    $value[1]
                );
                break;
            case self::PHONE:
                $builder->orderBy(
                    Order::select('phone')->whereColumn('orders.id', 'transactions.order_id'),
                    $value[1]
                );
                break;
            case self::TOTAL:
                $builder->orderBy(
                    Order::select('total')->whereColumn('orders.id', 'transactions.order_id'),
                    $value[1]
                );
                break;
            default:
                $builder->orderBy($value[0], $value[1]);
                break;
        }
    }
}