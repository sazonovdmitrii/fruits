<?php

namespace App\Enum;

enum OrderStatus: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case DELIVERY = 'delivery';
    case COMPLETED = 'completed';

    public function getLabel(): string
    {
        return match($this) {
            self::NEW => 'Новый',
            self::IN_PROGRESS => 'В процессе',
            self::DELIVERY => 'Доставка',
            self::COMPLETED => 'Выполнен',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::NEW => 'primary',
            self::IN_PROGRESS => 'warning',
            self::DELIVERY => 'info',
            self::COMPLETED => 'success',
        };
    }
}

