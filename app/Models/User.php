<?php

namespace App\Models;

use App\Enums\UserProfileDocumentType;
use App\Enums\UserProfileType;
use App\Models\Contracts\ValidationRules;
use App\Models\Dto\SellerReviewsCount;
use App\Models\Dto\UserAddSubscriptionDto;
use App\Notifications\UserEmailVerificationNotification;
use App\Notifications\UserProfileApprovedNotification;
use App\Notifications\UserProfileRejectedNotification;
use App\Notifications\UserResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User.
 *
 * @property int                                                                                                       $id
 * @property string                                                                                                    $email
 * @property bool                                                                                                      $is_active
 * @property string                                                                                                    $password
 * @property int                                                                                                       $posts_count_allowed
 * @property int                                                                                                       $spare_parts_count_allowed
 * @property float                                                                                                     $seller_rating
 * @property array                                                                                                     $seller_reviews_by_rating_count
 * @property null|string                                                                                               $remember_token
 * @property null|string                                                                                               $email_verified_at
 * @property null|\Illuminate\Support\Carbon                                                                           $created_at
 * @property null|\Illuminate\Support\Carbon                                                                           $updated_at
 * @property \Illuminate\Notifications\DatabaseNotification[]|\Illuminate\Notifications\DatabaseNotificationCollection $notifications
 * @property null|int                                                                                                  $notifications_count
 * @property null|\App\Models\UserProfile                                                                              $profile
 * @property string                                                                                                    $full_name
 * @property \App\Models\SparePart[]|\Illuminate\Database\Eloquent\Collection                                          $spareParts
 * @property null|int                                                                                                  $spare_parts_count
 * @property \App\Models\SparePart[]|\Illuminate\Database\Eloquent\Collection                                          $wishList
 * @property null|int                                                                                                  $wish_list_count
 * @property null|\Illuminate\Support\Carbon                                                                           $subscription_start_at
 * @property null|\Illuminate\Support\Carbon                                                                           $subscription_end_at
 * @property null|string                                                                                               $subscription_comment
 * @property bool                                                                                                      $subscription_request
 * @property bool                                                                                                      $had_subscription
 * @property int                                                                                                       $default_commission
 * @property int                                                                                                       $vip_commission
 * @property bool                                                                                                      $posts_request
 * @property bool                                                                                                      $can_upload_spare_parts
 *
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePostsCountAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSparePartsCountAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRating($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail, ValidationRules
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
        'default_commission',
        'vip_commission',
        'email_verified_at',
        'posts_count_allowed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = ['wishList', 'profile'];

    protected $casts = [
        'subscription_start_at'          => 'datetime',
        'subscription_end_at'            => 'datetime',
        'seller_reviews_by_rating_count' => 'array',
    ];

    public function validationRules(): array
    {
        return [
            'email'              => ['required', 'string', 'email'],
            'password'           => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'default_commission' => ['required', 'integer', 'min:0', 'max:100'],
            'vip_commission'     => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new UserEmailVerificationNotification());
    }

    public function setPasswordAttribute(string $value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    public function getFullNameAttribute(): string
    {
        $profile = $this->profile;

        if (!$profile) {
            return '';
        }

        return $profile->full_name;
    }

    public function sellerReviewsCount(): SellerReviewsCount
    {
        return new SellerReviewsCount(
            $this->seller_reviews_by_rating_count[1],
            $this->seller_reviews_by_rating_count[2],
            $this->seller_reviews_by_rating_count[3],
            $this->seller_reviews_by_rating_count[4],
            $this->seller_reviews_by_rating_count[5],
        );
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function hasProfile(): bool
    {
        return (bool) $this->profile;
    }

    public function hasActiveProfile(): bool
    {
        return $this->hasProfile() && $this->profile->hasActiveStatus();
    }

    public function canUploadSpareParts(): bool
    {
        return $this->can_upload_spare_parts || $this->profile
            ->documents()
            ->wherePivot('type', UserProfileDocumentType::SPARE_PARTS_UPLOAD_XLS)
            ->wherePivot('created_at', '>=', now()->subMonth())
            ->doesntExist();
    }

    public function addCanUploadSpareParts()
    {
        $this->can_upload_spare_parts = true;
        $this->save();
    }

    public function sparePartsUploaded()
    {
        $this->can_upload_spare_parts = false;
        $this->save();
    }

    public function isSeller(): bool
    {
        return $this->hasProfile()
        && in_array(
            $this->profile->type,
            [
                UserProfileType::INDIVIDUAL_ENTREPRENEUR,
                UserProfileType::LEGAL_PERSON,
            ],
            true
        );
    }

    public function hasVip(): bool
    {
        return $this->hasSubscription()
            && now()->greaterThanOrEqualTo($this->subscription_start_at)
            && now()->lessThan($this->subscription_end_at);
    }

    public function canAddPost(): bool
    {
        return $this->posts_count_allowed > 0;
    }

    public function hasSubscription(): bool
    {
        return $this->subscription_start_at && $this->subscription_end_at;
    }

    public function addSubscription(UserAddSubscriptionDto $dto)
    {
        $this->subscription_start_at = $dto->startDateTime();
        $this->subscription_end_at   = $dto->endDateTime();
        $this->subscription_comment  = $dto->comment();
        $this->subscription_request  = false;

        if (!$this->had_subscription) {
            $this->had_subscription = true;
        }

        $this->save();
    }

    public function removeSubscription()
    {
        $this->subscription_start_at = null;
        $this->subscription_end_at   = null;
        $this->subscription_comment  = null;
        $this->save();
    }

    public function addPosts(int $count)
    {
        $this->posts_count_allowed += $count;
        $this->posts_request = false;
        $this->save();
    }

    public function subscriptionRequest()
    {
        $this->subscription_request = true;
        $this->save();
    }

    public function postsRequest()
    {
        $this->posts_request = true;
        $this->save();
    }

    public function passwordComparison(string $password): bool
    {
        return \Hash::check($password, $this->password);
    }

    public function wishList(): BelongsToMany
    {
        return $this->belongsToMany(SparePart::class, 'wish_list', 'user_id', 'spare_part_id');
    }

    public function favoriteAds(): BelongsToMany
    {
        return $this->belongsToMany(Ad::class, 'favorite_ads', 'user_id', 'ad_id');
    }

    public function spareParts(): HasMany
    {
        return $this->hasMany(SparePart::class, 'seller_id', 'id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'user_id', 'id');
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class, 'seller_id', 'id')->latest('id');
    }

    public function sellerReviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Order::class, 'seller_id', 'order_id', 'id')
            ->with('order', function (BelongsTo $builder) {
                return $builder->select(['id', 'seller_id', 'buyer_id'])
                    ->with('buyer', function (BelongsTo $builder) {
                        return $builder->select(['id'])
                            ->without('wishList')
                            ->with('profile:id,user_id,first_name,last_name');
                    });
            })->latest('id');
    }

    public function scopeSellers(Builder $builder, ?string $status = null)
    {
        return $builder->whereHas(
            'profile',
            function (Builder $query) use ($status) {
                if ($status) {
                    $query->where('status', $status);
                }

                return $query->whereIn(
                    'type',
                    [
                        UserProfileType::INDIVIDUAL_ENTREPRENEUR,
                        UserProfileType::LEGAL_PERSON,
                    ]
                );
            }
        );
    }

    public function isModerationPending(): bool
    {
        return $this->hasProfile() && $this->profile->hasPendingStatus();
    }

    public function rejectSeller()
    {
        $this->checkProfileExists();

        $this->profile->rejectSeller();
        $this->notify(new UserProfileRejectedNotification());
    }

    public function approveSeller()
    {
        $this->checkProfileExists();

        $this->profile->approveSeller();
        $this->notify(new UserProfileApprovedNotification());
    }

    public function enable()
    {
        $this->is_active = true;

        $this->save();
    }

    public function disable()
    {
        $this->is_active = false;

        $this->save();
    }

    private function checkProfileExists()
    {
        if (!$this->profile) {
            throw new \LogicException('User profile doesn\'t exists');
        }
    }
}
