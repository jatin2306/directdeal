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
    }

    // Scope for category
    public function scopeFilterByCategory($query, $category)
    {
        if ($category) {
            return $query->where('property_category_id', $category);
        }
    }

    // Scope for child type
    public function scopeFilterByChildType($query, $childType)
    {
        if ($childType) {
            return $query->where('child_type_id', $childType);
        }
    }

    public function scopeFilterByPrice($query, $priceMin, $priceMax)
    {
        if ($priceMin !== null && $priceMax !== null) {
            $query->whereBetween('price', [$priceMin, $priceMax]);
        }
    }


    // Scope for status
    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }
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
