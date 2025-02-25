<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'fecha', 'total'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'compra_producto', 'compra_id', 'producto_id')->withPivot('cantidad');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'compra_id');
    }






}
