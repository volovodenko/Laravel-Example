<?php

namespace App\Models;

use App\Enums\DeliveryMethod as DeliveryMethodEnum;
use App\Enums\UserProfileContractType;
use App\Enums\UserProfileDocumentType;
use App\Enums\UserProfileStatus;
use App\Enums\UserProfileType;
use App\Models\Contracts\ValidationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Validation\Rule;

/**
 * App\Models\UserProfile.
 *
 * @property int                                                         $id
 * @property int                                                         $user_id
 * @property string                                                      $type
 * @property string                                                      $status
 * @property string                                                      $phone
 * @property string                                                      $first_name
 * @property string                                                      $last_name
 * @property null|string                                                 $contract_type
 * @property null|string                                                 $contact_phone
 * @property null|string                                                 $iban
 * @property null|string                                                 $mfo
 * @property null|string                                                 $tax_number
 * @property null|string                                                 $registration_address
 * @property null|string                                                 $edrpou
 * @property null|string                                                 $physical_address
 * @property null|string                                                 $organization_name
 * @property null|string                                                 $warehouse_manager_name
 * @property null|string                                                 $warehouse_manager_phone
 * @property null|\Illuminate\Support\Carbon                             $buyer_at
 * @property null|\Illuminate\Support\Carbon                             $seller_at
 * @property null|\Illuminate\Support\Carbon                             $created_at
 * @property null|\Illuminate\Support\Carbon                             $updated_at
 * @property \App\Models\User                                            $user
 * @property \App\Models\File[]|\Illuminate\Database\Eloquent\Collection $documents
 * @property null|int                                                    $documents_count
 * @property string                                                      $city
 * @property null|string                                                 $binotel_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereContractType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereEdrpou($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereMfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereOrganizationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile wherePhysicalAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereRegistrationAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereWarehouseManagerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereWarehouseManagerPhone($value)
 * @mixin \Eloquent
 */
class UserProfile extends Model implements ValidationRules
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'phone',
        'first_name',
        'last_name',
        'contract_type',
        'contact_phone',
        'iban',
        'mfo',
        'tax_number',
        'registration_address',
        'edrpou',
        'physical_address',
        'organization_name',
        'warehouse_manager_name',
        'warehouse_manager_phone',
    ];

    protected $casts = [
        'buyer_at'  => 'datetime',
        'seller_at' => 'datetime',
    ];

    public function validationRules(): array
    {
        $string = ['required', 'string', 'min:8', 'max:255'];

        return [
            'city' => ['required', 'string', 'min:3', 'max:255'],
            // PHYSICAL_PERSON
            'type'       => ['required', 'string', Rule::in(UserProfileType::values())],
            'phone'      => ['required', 'phone'],
            'first_name' => ['required', 'string', 'cyrillic', 'min:3', 'max:255'],
            'last_name'  => ['required', 'string', 'cyrillic', 'min:3', 'max:255'],
            // INDIVIDUAL_ENTREPRENEUR
            'iban'                     => ['required', 'iban'],
            'mfo'                      => ['required', 'mfo'],
            'registration_address'     => $string,
            'tax_number'               => ['required', 'tax_number'],
            'contact_phone'            => ['required', 'phone'],
            'registration_certificate' => ['required', 'mimes:jpg,jpeg,png', 'max:5120'],
            'contract_type'            => ['required', 'string', Rule::in([UserProfileContractType::PAPER])],
            // LEGAL_PERSON
            'edrpou'                  => ['required', 'edrpou'],
            'physical_address'        => $string,
            'organization_name'       => $string,
            'warehouse_manager_name'  => $string,
            'warehouse_manager_phone' => ['required', 'phone'],
        ];
    }

    public function settingsDeliveryMethods(): array
    {
        return [
            DeliveryMethodEnum::NEW_POST_DEPARTMENT_DELIVERY,
            DeliveryMethodEnum::NEW_POST_COURIER_DELIVERY,
            DeliveryMethodEnum::DELIVERY_DEPARTMENT_DELIVERY,
            DeliveryMethodEnum::DELIVERY_COURIER_DELIVERY,
        ];
    }

    public function statusAsEnum(): UserProfileStatus
    {
        return new UserProfileStatus($this->status);
    }

    public function typeAsEnum(): UserProfileType
    {
        return new UserProfileType($this->type);
    }

    public function contractTypeAsEnum(): UserProfileContractType
    {
        return new UserProfileContractType($this->contract_type);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'user_profile_documents', 'user_profile_id', 'file_id')
            ->using(UserProfileDocument::class)
            ->withPivot('type')
            ->withTimestamps();
    }

    public function deliveryMethods(): BelongsToMany
    {
        return $this->belongsToMany(DeliveryMethod::class, 'user_profile_delivery_method', 'user_profile_id', 'delivery_method', 'id', 'name');
    }

    public function registrationCertificate(): ?File
    {
        return $this->documents()
            ->wherePivot('type', UserProfileDocumentType::REGISTRATION_CERTIFICATE)
            ->first();
    }

    public function hasActiveStatus(): bool
    {
        return $this->statusAsEnum()->equals(UserProfileStatus::ACTIVE());
    }

    public function hasPendingStatus(): bool
    {
        return $this->statusAsEnum()->equals(UserProfileStatus::MODERATION_PENDING());
    }

    public function hasPhysicalPersonType(): bool
    {
        return $this->typeAsEnum()->equals(UserProfileType::PHYSICAL_PERSON());
    }

    public function hasIndividualEntrepreneurType(): bool
    {
        return $this->typeAsEnum()->equals(UserProfileType::INDIVIDUAL_ENTREPRENEUR());
    }

    public function hasLegalPersonType(): bool
    {
        return $this->typeAsEnum()->equals(UserProfileType::LEGAL_PERSON());
    }

    public function getFullNameAttribute(): string
    {
        // Full name for 1S service, Binotel. Also is used in User model
        return $this->first_name . ' ' . $this->last_name;
    }

    public function rejectSeller()
    {
        $this->status = UserProfileStatus::REJECTED;
        $this->type   = UserProfileType::PHYSICAL_PERSON;

        if (!$this->buyer_at) {
            $this->buyer_at = now();
        }

        $this->save();
    }

    public function approveSeller()
    {
        $this->status = UserProfileStatus::ACTIVE;

        if (!$this->seller_at) {
            $this->seller_at = now();
            $this->deliveryMethods()->sync($this->settingsDeliveryMethods());
        }

        $this->save();
    }
}
