<?php

namespace App\Models;

use App\Models\Enums\UserProfileDocumentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\UserProfileDocument.
 *
 * @property int                             $id
 * @property int                             $user_profile_id
 * @property int                             $file_id
 * @property string                          $type
 * @property null|\Illuminate\Support\Carbon $created_at
 * @property null|\Illuminate\Support\Carbon $updated_at
 * @property \App\Models\UserProfile         $profile
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfileDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfileDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfileDocument query()
 * @mixin \Eloquent
 */
class UserProfileDocument extends Pivot
{
    protected $table = 'user_profile_documents';

    public $incrementing = true;

    public function typeAsEnum(): UserProfileDocumentType
    {
        return new UserProfileDocumentType($this->type);
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id', 'id');
    }
}
