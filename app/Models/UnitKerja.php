<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    use HasFactory;
    protected $fillable = ['nama_unit', 'lokasi'];

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }
}