<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public const DEFAULT_THEME = 'royal_navy';

    protected $fillable = ['user_id', 'name', 'slug', 'description', 'logo_path', 'hero_image_path', 'theme'];

    public static function themePresets(): array
    {
        return [
            'royal_navy' => [
                'label' => 'أزرق ملكي',
                'description' => 'طابع احترافي وفخم',
                'primary' => '#0d1b4b',
                'primary_hover' => '#1a2d6b',
                'accent' => '#d4af37',
                'accent_soft' => '#fff4cf',
            ],
            'emerald_forest' => [
                'label' => 'أخضر زمردي',
                'description' => 'إحساس طبيعي وهادئ',
                'primary' => '#0f5132',
                'primary_hover' => '#146c43',
                'accent' => '#38b27d',
                'accent_soft' => '#dff7ec',
            ],
            'sunset_coral' => [
                'label' => 'مرجاني غروب',
                'description' => 'ستايل دافئ وحيوي',
                'primary' => '#8b2e2e',
                'primary_hover' => '#a33939',
                'accent' => '#ef8354',
                'accent_soft' => '#ffe7dc',
            ],
            'twilight_indigo' => [
                'label' => 'نيلي غامق',
                'description' => 'مودرن ولمسة تقنية',
                'primary' => '#2f2a6b',
                'primary_hover' => '#3d368a',
                'accent' => '#8f7dff',
                'accent_soft' => '#ece8ff',
            ],
            'desert_amber' => [
                'label' => 'عنبر صحراوي',
                'description' => 'دافئ وكلاسيكي',
                'primary' => '#6b4a1f',
                'primary_hover' => '#805725',
                'accent' => '#d9a441',
                'accent_soft' => '#fdf0d8',
            ],
        ];
    }

    public static function themeKeys(): array
    {
        return array_keys(self::themePresets());
    }

    public static function resolveTheme(?string $key): array
    {
        $themes = self::themePresets();
        $resolvedKey = $key ?: self::DEFAULT_THEME;

        if (! array_key_exists($resolvedKey, $themes)) {
            $resolvedKey = self::DEFAULT_THEME;
        }

        return [
            'key' => $resolvedKey,
            ...$themes[$resolvedKey],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }
}
