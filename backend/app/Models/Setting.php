<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'gen_settings_super';
    protected $primaryKey = 'settings_id_sup';
    public $timestamps = false;

    protected $fillable = [
        'label',
        'value',
    ];

    public static function get(string $key, $default = null)
    {
        try {
            $setting = DB::table('gen_settings_super')->where('label', $key)->first();
            if ($setting && !empty($setting->value)) {
                return $setting->value;
            }
        } catch (\Throwable $e) {}
        return $default;
    }

    public static function set(string $key, $value)
    {
        try {
            return DB::table('gen_settings_super')->updateOrInsert(['label' => $key], ['value' => $value]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
