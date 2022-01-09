<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use App\Resources\UrlResult;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Url extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'slug', 'valid'];

    /**
     * Properties that need to be treated as date.
     *
     * @var array
     */
    protected $dates = ['valid'];

    /**
     * Creates slug from URL.
     */
    public static function createSlug(string $url, string $baseUrl): UrlResult
    {
        // If the option allow multiple is turned off and there is a slug for this url return the existing one.
        if (!config('url.allow_multiple')) {
            $checkIfUrlExists = self::getSlugFromUrl($url);

            if ($checkIfUrlExists) {
                return new UrlResult($baseUrl . $checkIfUrlExists->slug, 200);
            }
        }

        // If check before option is enabled, check whether a call to the URL will return an error.
        if (config('url.check_before') && !self::isValidUrl($url)) {
            return new UrlResult(null, 422, 'The URL you are trying to shorten is invalid');
        }

        // Create and return the new slug
        $url = self::create([
            'slug' => self::createNewSlug(),
            'url' => $url,
            'valid' => Carbon::now()->addDays(config('url.valid_days'))
        ]);

        return new UrlResult($baseUrl . $url->slug, 201);
    }

    /**
     * Get the slug
     */
    protected static function getSlugFromUrl(string $url): ?Url
    {
        $urls = self::where('url', $url)->latest()->get();

        foreach ($urls as $optUrl) {
            // If the URL is not expired return the slug.
            if ($optUrl->expired() === false) {
                return $optUrl;
            }
        }

        return null;
    }

    /**
     * Checks whether provided URL response.
     */
    protected static function isValidUrl(string $url): bool
    {
        try {
            return Http::get('http://' . str_replace(['http://', 'https://'], '', $url))->status() < 300;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Creates a slug not yet used.
     * @throws Exception
     */
    protected static function createNewSlug(): string
    {
        do {
            $slug = substr(
                str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 10)),
                0,
                random_int(5, 10)
            );
        } while (self::where('slug', $slug)->count());

        return $slug;
    }

    /**
     * Returns URL from slug
     */
    public static function showUrl(string $slug, ?string $ip = null): UrlResult
    {
        $instance = self::getUrlFromSlug($slug);

        // Validate slug
        if (!$instance) {
            return new UrlResult(null, 404, 'Not found');
        }

        //Register a "click" into the shortened URL
        $instance->clicks()->create(['ip' => $ip]);

        // If the "Renovate on Access" option is on, every access
        // refreshes the URL expiration time
        if (config('url.renovate_on_access')) {
            $instance->update([
                'valid' => Carbon::now()->addDays(config('url.valid_days'))
            ]);
        }

        return new UrlResult('http://' . str_replace(['http://', 'https://'], '', $instance->url));
    }

    /**
     * Checks for slug. If so, return your URL.
     */
    protected static function getUrlFromSlug(string $slug): ?Url
    {
        $urls = self::where('slug', $slug)->latest()->get();

        foreach ($urls as $optUrl) {
            if ($optUrl->expired() === false) {
                return $optUrl;
            }
        }

        return null;
    }

    /**
     * The "has many" relationship to URL clicks.
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    /**
     * Checks if the current instance has expired
     */
    protected function expired(): bool
    {
        return Carbon::now()->diffInSeconds($this->valid, false) < 0;
    }
}
