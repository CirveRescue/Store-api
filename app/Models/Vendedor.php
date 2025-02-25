<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'vendedores';

    protected $fillable = ['nombre', 'email', 'contrasena'];


    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function tiendas()
    {
        return $this->hasMany(Tienda::class);
    }

    


}
