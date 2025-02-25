<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'tienda_id'];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }

    public function carritos()
    {
        return $this->belongsToMany(Carrito::class, 'carrito_producto', 'producto_id', 'carrito_id')->withPivot('cantidad');
    }

    public function compras()
    {
        return $this->belongsToMany(Compra::class, 'compra_producto', 'producto_id', 'compra_id')->withPivot('cantidad');
    }

    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    
}
