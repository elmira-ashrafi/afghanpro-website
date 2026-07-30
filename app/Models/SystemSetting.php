<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type', // 'string', 'integer', 'float', 'boolean', 'json'
        'description',
        'group',
    ];

    protected $casts = [
        'value' => 'string', // Will be cast dynamically
    ];

    /**
     * Get the cast value based on the type.
     */
    public function getCastValue()
    {
        switch ($this->type) {
            case 'integer':
                return (int) $this->value;
            case 'float':
                return (float) $this->value;
            case 'boolean':
                return (bool) $this->value;
            case 'json':
                return json_decode($this->value, true);
            default:
                return $this->value;
        }
    }

    /**
     * Set value with appropriate conversion.
     */
    public function setValueWithType($value)
    {
        switch ($this->type) {
            case 'json':
                $this->value = json_encode($value);
                break;
            case 'boolean':
                $this->value = $value ? '1' : '0';
                break;
            default:
                $this->value = (string) $value;
        }

        return $this;
    }

    /**
     * Get a setting by key.
     */
    public static function getSetting($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return $setting->getCastValue();
    }

    /**
     * Set a setting by key.
     */
    public static function setSetting($key, $value, $type = 'string', $description = null, $group = 'general')
    {
        $setting = self::firstOrNew(['key' => $key]);
        
        if (!$setting->exists) {
            $setting->type = $type;
            $setting->description = $description;
            $setting->group = $group;
        }
        
        $setting->setValueWithType($value);
        $setting->save();
        
        return $setting;
    }
} 