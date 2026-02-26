<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PropertyPicture;
use App\Models\Category;
use App\Models\ChildType;
use App\Models\User;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'propertyType',
        'property_category_id',
        'child_type_id',
        'propertyName',
        'address',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'builtArea',
        'plotArea',
        'unitNo',
        'floorNo',
        'furnished',
        'balcony',
        'community',
        'view',
        'parking',
        'status',
        'any_upgrades',
        'communityFee',
        'mortgaged',
        'price',
        'amenities', // Ensure this is cast properly as JSON in the database.
        'city',
        'sub_area',
        'is_featured',
        'verified',
        'needs_photographer',
        'description',
        'reference',
        'broker_license',
        'zone_name',
        'dld_permit_number',
        'agent_license',
        'regulatory_image',
    ];
    

    protected $casts = [
        'amenities' => 'array',
    ];
    
    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relationship with Review model
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    // Relationship with PropertyPicture model
    public function pictures()
    {
        return $this->hasMany(PropertyPicture::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'property_category_id');
    }


    public function childTypeRelation()
    {
        return $this->belongsTo(ChildType::class, 'child_type_id');
    }

    // Accessor for average rating    
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }


    // Scope for property type
    public function scopeFilterByPropertyType($query, $propertyType)
    {
        if ($propertyType) {
            return $query->where('propertyType', $propertyType);
        }
        return $query;
    }

    // Scope for category
    public function scopeFilterByCategory($query, $category)
    {
        if ($category) {
            return $query->where('property_category_id', $category);
        }
        return $query;
    }

    // Scope for child type
    public function scopeFilterByChildType($query, $childType)
    {
        if ($childType) {
            return $query->where('child_type_id', $childType);
        }
        return $query;
    }

    public function scopeFilterByPrice($query, $priceMin, $priceMax)
    {
        if ($priceMin !== null && $priceMax !== null) {
            $query->whereBetween('price', [$priceMin, $priceMax]);
        }
        return $query;
    }


    // Scope for status
    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Scope for verified (admin: Verified / Not Verified)
    public function scopeFilterByVerified($query, $verified)
    {
        if ($verified !== null && $verified !== '') {
            return $query->where('verified', (bool) (int) $verified);
        }
        return $query;
    }
    // Scope for Bedrooms Bathrooms and Location

    public function scopeFilterByBedrooms($query, $bedrooms)
    {
        if ($bedrooms) {
            return $query->where('bedrooms', $bedrooms);
        }
        return $query;
    }

    public function scopeFilterByBathrooms($query, $bathrooms)
    {
        if ($bathrooms) {
            return $query->where('bathrooms', $bathrooms);
        }
        return $query;
    }

    public function scopeFilterByLocation($query, $location)
    {
        if ($location) {
            return $query->where(function ($query) use ($location) {
                $query->where('city', 'LIKE', '%' . $location . '%')
                      ->orWhere('sub_area', 'LIKE', '%' . $location . '%');
            });
        }
        return $query;
    }
    // Scope for Verified properties
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function isFavoritedBy($user)
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'property_user_subscriptions')
                    ->withPivot('notification_type')
                    ->withTimestamps();
    }

    /**
     * Admin panel: search across all relevant text/numeric fields (case-insensitive).
     */
    public function scopeAdminSearch($query, $term)
    {
        if ($term === null || $term === '') {
            return $query;
        }

        $term = trim($term);
        if ($term === '') {
            return $query;
        }

        $pattern = '%' . mb_strtolower($term) . '%';
        $termLower = mb_strtolower($term);

        return $query->where(function ($q) use ($term, $termLower, $pattern) {
            // Type: Buy / Rent / Off Plan
            if (str_contains($termLower, 'buy')) {
                $q->orWhere('propertyType', '1');
            }
            if (str_contains($termLower, 'rent')) {
                $q->orWhere('propertyType', '2');
            }
            if (str_contains($termLower, 'off plan') || str_contains($termLower, 'offplan')) {
                $q->orWhere('propertyType', '3');
            }
            // Status: Verified / Not verified
            if (str_contains($termLower, 'unverified') || (str_contains($termLower, 'not') && str_contains($termLower, 'verified'))) {
                $q->orWhere('verified', false);
            } elseif (str_contains($termLower, 'verified')) {
                $q->orWhere('verified', true);
            }
            // Case-insensitive text search (e.g. "marwa" matches "Marwa Homes")
            $q->whereRaw('LOWER(propertyName) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(address, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(city, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(sub_area, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(community, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(description, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(reference, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(unitNo, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(zone_name, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(dld_permit_number, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(broker_license, "")) LIKE ?', [$pattern])
                ->orWhereRaw('LOWER(COALESCE(agent_license, "")) LIKE ?', [$pattern])
                // Posted By: search related user name and email
                ->orWhereHas('user', function ($userQuery) use ($pattern) {
                    $userQuery->whereRaw('LOWER(name) LIKE ?', [$pattern])
                        ->orWhereRaw('LOWER(COALESCE(email, "")) LIKE ?', [$pattern]);
                });
            // Numeric: match price or id if term looks like a number
            if (is_numeric($term)) {
                $q->orWhere('price', (float) $term)
                    ->orWhere('id', (int) $term)
                    ->orWhere('bedrooms', (int) $term)
                    ->orWhere('bathrooms', (int) $term);
            }
        });
    }

    public function scopeSmartSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        $searchLower = strtolower($search);

        // Detect "2 bed", "3 bedroom", "studio"
        preg_match('/(\d+)\s*bed/', $searchLower, $bedMatch);
        $bedrooms = $bedMatch[1] ?? null;

        if (str_contains($searchLower, 'studio')) {
            $bedrooms = 0;
        }

        return $query->where(function ($q) use ($search, $bedrooms) {

            // Text-based matching
            $q->where('propertyName', 'LIKE', "%{$search}%")
            ->orWhere('address', 'LIKE', "%{$search}%")
            ->orWhere('city', 'LIKE', "%{$search}%")
            ->orWhere('sub_area', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%");

            // Bedrooms matching
            if ($bedrooms !== null) {
                $q->orWhere('bedrooms', (int) $bedrooms);
            }
        });
    }

    public function userListing()
    {
        return $this->hasOne(\App\Models\UserListing::class, 'property_id');
    }



}
