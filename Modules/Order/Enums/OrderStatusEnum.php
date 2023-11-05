<?php

namespace Modules\Order\Enums;

enum OrderStatusEnum: string
{
    case NOT_PAID = 'not_paid';
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
}
