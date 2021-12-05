<?php

namespace App\Models;

use App\Enums\SparePartCondition;
use App\Models\Contracts\ValidationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder;
use Illuminate\Validation\Rule;

/**
 * App\Models\SparePart.
 *
 * @property int                                                         $id
 * @property int                                                         $seller_id
 * @property string                                                      $vendor_code
 * @property null|string                                                 $vendor_name
 * @property string                                                      $private_name
 * @property null|string                                                 $public_name
 * @property string                                                      $article_number
 * @property null|string                                                 $description
 * @property string                                                      $city
 * @property int                                                         $quantity
 * @property int                                                         $reserved_quantity
 * @property int                                                         $price
 * @property bool                                                        $is_vat
 * @property string                                                      $condition
 * @property bool                                                        $is_oversized
 * @property bool                                                        $is_checked
 * @property null|int                                                    $weight
 * @property null|int                                                    $height
 * @property null|int                                                    $width
 * @property null|int                                                    $depth
 * @property bool                                                        $is_active
 * @property null|\Illuminate\Support\Carbon                             $created_at
 * @property null|\Illuminate\Support\Carbon                             $updated_at
 * @property \App\Models\User                                            $seller
 * @property string                                                      $fraction_of_price
 * @property string                                                      $int_of_price
 * @property string                                                      $short_description
 * @property string                                                      $short_public_name
 * @property string                                                      $weight_in_kilo
 * @property \App\Models\File[]|\Illuminate\Database\Eloquent\Collection $photos
 * @property null|int                                                    $photos_count
 *
 * @method static \Database\Factories\SparePartFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart query()
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereArticleNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereIsChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereIsOversized($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereIsVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart wherePrivateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart wherePublicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereVendorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereVendorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparePart whereReservedQuantity($value)
 * @mixin \Eloquent
 */
class SparePart extends Model implements ValidationRules
{
    use HasFactory;

    private const SHORT_NAME_SYMBOLS_COUNT = 100;

    protected $fillable = [
        'seller_id',
        'vendor_code',
        'vendor_name',
        'private_name',
        'public_name',
        'article_number',
        'description',
        'city',
        'quantity',
        'price',
        'is_vat',
        'condition',
        'is_oversized',
        'is_checked',
        'weight',
        'height',
        'width',
        'depth',
    ];

    public function validationRules(): array
    {
        $string          = ['required', 'string', 'min:3', 'max:255'];
        $nullableString  = ['nullable', 'string', 'min:3', 'max:255'];
        $nullableInteger = ['nullable', 'integer', 'min:1', 'max:1000000'];

        return [
            'vendor_code'    => $string,
            'vendor_name'    => $nullableString,
            'private_name'   => $string,
            'public_name'    => $nullableString,
            'article_number' => $string, // use unique rule for custom case (unique by two columns:article_number,seller_id)
            'description'    => ['nullable', 'string', 'min:8', 'max:4096'],
            'city'           => ['required', 'string', 'min:1', 'max:255'],
            'quantity'       => ['required', 'integer', 'min:1', 'max:1000000'],
            'price'          => ['required', 'numeric', 'between:0,999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'is_vat'         => ['boolean'],
            'condition'      => ['required', 'string', Rule::in(SparePartCondition::keys())],
            'is_oversized'   => ['boolean'],
            'is_checked'     => ['boolean'],
            'weight'         => $nullableInteger,
            'height'         => $nullableInteger,
            'width'          => $nullableInteger,
            'depth'          => $nullableInteger,
            'photos'         => [
                'array',
                'max:5',
                Rule::exists('files', 'id')
                    ->using(function (DatabaseQueryBuilder $query) {
                        return $query->where('mime_type', 'like', 'image/%')
                            ->where('size', '<=', '5242880');
                    }),
            ],
        ];
    }

    public function conditionAsEnum(): SparePartCondition
    {
        return new SparePartCondition($this->attributes['condition']);
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (int) ($value * 100);
    }

    public function setIsVatAttribute($value)
    {
        $this->attributes['is_vat'] = (bool) $value;
    }

    public function setIsOversizedAttribute($value)
    {
        $this->attributes['is_oversized'] = (bool) $value;
    }

    public function setIsCheckedAttribute($value)
    {
        $this->attributes['is_checked'] = (bool) $value;
    }

    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function getShortPublicNameAttribute(): string
    {
        $name = $this->public_name ?? $this->private_name;

        return \Str::limit($name, self::SHORT_NAME_SYMBOLS_COUNT);
    }

    public function getShortDescriptionAttribute(): string
    {
        return (string) \Str::limit($this->description, self::SHORT_NAME_SYMBOLS_COUNT);
    }

    public function getWeightInKiloAttribute(): string
    {
        if (!$this->weight) {
            return '';
        }

        return (string) round($this->weight / 1000, 1);
    }

    public function getIntOfPriceAttribute(): string
    {
        return explode('.', format_to_money($this->price))[0];
    }

    public function getFractionOfPriceAttribute(): string
    {
        return explode('.', format_to_money($this->price))[1];
    }

    public function disable()
    {
        $this->is_active = false;
        $this->save();
    }

    public function enable()
    {
        $this->is_active = true;
        $this->save();
    }

    public function selfDestroy()
    {
        \DB::transaction(function () {
            $this->photos()->detach();
            $this->delete();
        });
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    public function sellerProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'seller_id', 'user_id');
    }

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'spare_part_photos', 'spare_part_id', 'photo_id');
    }

    public function pivotIntOfPrice(bool $withQuantity = false)
    {
        $price = $withQuantity ? $this->pivot->price * $this->pivot->quantity : $this->pivot->price;

        return explode('.', format_to_money($price))[0];
    }

    public function pivotFractionOfPrice(bool $withQuantity = false)
    {
        $price = $withQuantity ? $this->pivot->price * $this->pivot->quantity : $this->pivot->price;

        return explode('.', format_to_money($price))[1];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function canAddToCart(?int $quantity = 1): bool
    {
        if (\Auth::id() === $this->seller_id) {
            return false;
        }

        return $this->is_active && $this->quantity > 0 && $this->quantity >= $quantity;
    }
}
