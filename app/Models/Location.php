<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'image',
        'required_level',
        'is_active',
        'can_have_events',
        'coordinates'
    ];

    protected $casts = [
        'required_level' => 'integer',
        'is_active' => 'boolean',
        'can_have_events' => 'boolean',
        'coordinates' => 'array'
    ];

    /**
     * Get the shops in this location.
     */
    public function shops()
    {
        return $this->hasMany(LocationShop::class);
    }

    /**
     * Get the users who have visited this location.
     */
    public function visitors()
    {
        return $this->belongsToMany(User::class, 'user_locations')
            ->withPivot('visit_count', 'first_visited_at', 'last_visited_at')
            ->withTimestamps();
    }

    /**
     * Get users currently at this location.
     */
    public function currentVisitors()
    {
        return $this->hasMany(User::class, 'current_location_id');
    }

    /**
     * Get active inns in this location.
     */
    public function inns()
    {
        return $this->shops()->where('type', 'inn')->where('is_active', true);
    }

    /**
     * Check if a user can access this location.
     */
    public function canBeAccessedBy(User $user)
    {
        return $user->level >= $this->required_level && $this->is_active;
    }
}
