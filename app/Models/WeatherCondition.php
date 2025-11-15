<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeatherCondition extends Model
{
    protected $fillable = [
        'location_id',
        'map_slug',
        'date',
        'season',
        'condition',
        'temperature',
        'precipitation',
        'wind_speed',
        'effects',
        'special_event',
    ];

    protected $casts = [
        'date' => 'date',
        'effects' => 'array',
    ];

    /**
     * Get the location.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get condition label.
     */
    public function getConditionLabelAttribute()
    {
        return match($this->condition) {
            'sunny' => 'Soleggiato',
            'cloudy' => 'Nuvoloso',
            'rainy' => 'Piovoso',
            'stormy' => 'Tempestoso',
            'snowy' => 'Nevoso',
            'foggy' => 'Nebbioso',
            'windy' => 'Ventoso',
            'magical_storm' => 'Tempesta Magica',
            default => 'Sconosciuto'
        };
    }

    /**
     * Get weather icon.
     */
    public function getIconAttribute()
    {
        return match($this->condition) {
            'sunny' => 'fa-sun',
            'cloudy' => 'fa-cloud',
            'rainy' => 'fa-cloud-rain',
            'stormy' => 'fa-bolt',
            'snowy' => 'fa-snowflake',
            'foggy' => 'fa-smog',
            'windy' => 'fa-wind',
            'magical_storm' => 'fa-magic',
            default => 'fa-cloud'
        };
    }

    /**
     * Get season label.
     */
    public function getSeasonLabelAttribute()
    {
        return match($this->season) {
            'spring' => 'Primavera',
            'summer' => 'Estate',
            'autumn' => 'Autunno',
            'winter' => 'Inverno',
            default => 'Sconosciuta'
        };
    }

    /**
     * Check if weather affects gameplay.
     */
    public function hasEffects()
    {
        return !empty($this->effects);
    }

    /**
     * Get current weather for location.
     */
    public static function getCurrentWeather($locationId = null, $mapSlug = null)
    {
        $query = static::whereDate('date', Carbon::today());

        if ($locationId) {
            $query->where('location_id', $locationId);
        } elseif ($mapSlug) {
            $query->where('map_slug', $mapSlug);
        }

        return $query->first();
    }

    /**
     * Generate random weather for season.
     */
    public static function generateForSeason($season, $locationId = null, $mapSlug = null)
    {
        $conditions = [
            'spring' => ['sunny', 'cloudy', 'rainy', 'windy'],
            'summer' => ['sunny', 'sunny', 'cloudy', 'stormy'],
            'autumn' => ['cloudy', 'rainy', 'windy', 'foggy'],
            'winter' => ['snowy', 'cloudy', 'foggy', 'stormy'],
        ];

        $seasonConditions = $conditions[$season] ?? ['cloudy'];
        $condition = $seasonConditions[array_rand($seasonConditions)];

        $tempRanges = [
            'spring' => [10, 20],
            'summer' => [20, 30],
            'autumn' => [5, 15],
            'winter' => [-5, 5],
        ];

        [$minTemp, $maxTemp] = $tempRanges[$season];
        $temperature = rand($minTemp, $maxTemp);

        return static::create([
            'location_id' => $locationId,
            'map_slug' => $mapSlug,
            'date' => Carbon::today(),
            'season' => $season,
            'condition' => $condition,
            'temperature' => $temperature,
            'precipitation' => in_array($condition, ['rainy', 'snowy', 'stormy']) ? rand(40, 90) : rand(0, 30),
            'wind_speed' => in_array($condition, ['windy', 'stormy']) ? rand(30, 60) : rand(5, 20),
        ]);
    }
}
