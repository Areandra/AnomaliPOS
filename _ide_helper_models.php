<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $restaurant_id
 * @property int $order_id
 * @property int $order_item_id
 * @property string $kot_number
 * @property string $section
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\OrderItem $orderItem
 * @property-read \App\Models\Restaurant $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot whereKotNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kot whereUpdatedAt($value)
 */
	class Kot extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $restaurant_id
 * @property string $name
 * @property string|null $description
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Restaurant $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereUpdatedAt($value)
 */
	class MenuCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $category_id
 * @property int $restaurant_id
 * @property string $name
 * @property string|null $description
 * @property numeric $price
 * @property numeric|null $cost_of_goods
 * @property string|null $image_url
 * @property bool $is_available
 * @property string|null $sku
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MenuCategory $category
 * @property-read \App\Models\Restaurant $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCostOfGoods($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereUpdatedAt($value)
 */
	class MenuItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $table_id
 * @property int $restaurant_id
 * @property int|null $table_session_id
 * @property string $order_code
 * @property string $type
 * @property string $status
 * @property numeric $subtotal
 * @property numeric $tax
 * @property numeric $discount
 * @property numeric $total
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Payment|null $payment
 * @property-read \App\Models\Restaurant $restaurant
 * @property-read \App\Models\Table|null $table
 * @property-read \App\Models\TableSession|null $tableSession
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTableSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $restaurant_id
 * @property int $menu_item_id
 * @property int $quantity
 * @property numeric $price
 * @property numeric $subtotal
 * @property string|null $notes
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MenuItem $menuItem
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Restaurant $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereMenuItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property string $payment_method
 * @property int $restaurant_id
 * @property int $shift_id
 * @property numeric $amount
 * @property numeric $change
 * @property string $status
 * @property \Illuminate\Support\Carbon $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $createdBy
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Restaurant $restaurant
 * @property-read \App\Models\Shift $shift
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUserId($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $plan
 * @property string|null $avatar_url
 * @property string $status
 * @property string $pin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kot> $kots
 * @property-read int|null $kots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuCategory> $menuCategories
 * @property-read int|null $menu_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $menuItems
 * @property-read int|null $menu_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shift> $shifts
 * @property-read int|null $shifts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TableSession> $tableSessions
 * @property-read int|null $table_sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Table> $tables
 * @property-read int|null $tables_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant wherePin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereUpdatedAt($value)
 */
	class Restaurant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $restaurant_id
 * @property string $status
 * @property numeric $modal_awal
 * @property \Illuminate\Support\Carbon $opened_at
 * @property numeric $cash_system
 * @property numeric $cash_physical
 * @property numeric $cash_variance
 * @property numeric $qris_system
 * @property numeric $debit_system
 * @property numeric $transfer_system
 * @property \Illuminate\Support\Carbon|null $closed_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Restaurant $restaurant
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCashPhysical($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCashSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCashVariance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereDebitSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereModalAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereOpenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereQrisSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereTransferSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereUserId($value)
 */
	class Shift extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $restaurant_id
 * @property string $table_number
 * @property int $capacity
 * @property numeric|null $position_x
 * @property numeric|null $position_y
 * @property bool $facing
 * @property bool $vertical
 * @property string $status
 * @property int|null $current_table_session_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Restaurant $restaurant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TableSession> $tableSessions
 * @property-read int|null $table_sessions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereCurrentTableSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereFacing($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table wherePositionX($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table wherePositionY($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereTableNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereVertical($value)
 */
	class Table extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $table_id
 * @property int $restaurant_id
 * @property int $created_by
 * @property string $token
 * @property \Illuminate\Support\Carbon $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Restaurant $restaurant
 * @property-read \App\Models\Table $table
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableSession whereUpdatedAt($value)
 */
	class TableSession extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $restaurant_id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string $status
 * @property string|null $avatar_url
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Restaurant $restaurant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shift> $shifts
 * @property-read int|null $shifts_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

