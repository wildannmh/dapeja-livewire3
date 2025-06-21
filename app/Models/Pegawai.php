<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = ['nip', 'nama', 'jabatan_id', 'unit_kerja_id', 'gaji'];

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function cuti(): HasMany
    {
        return $this->hasMany(Cuti::class);
    }

    // Accessor untuk Gaji Total (Gaji Pokok + Tunjangan)
    protected function gajiTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->gaji + $this->jabatan->tunjangan,
        );
    }
}