<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['nombre', 'email', 'contrasena'];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function carritos()
    {
        return $this->hasMany(Carrito::class);
    }

    


}
