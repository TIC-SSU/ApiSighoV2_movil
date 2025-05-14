<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoPDF extends Model
{
    use HasFactory;
    protected $table = "administracion.documentos_pdf";
    protected $connection = "pgsql";

    public $timestamps = false; 
    
    protected $fillable = [
        //"id", es auto incrementable
        "id_tipo_documento",
        "codigo_seguridad",
        "modulo",
        "fecha_generado",
        "cantidad_paginas",
        "pertenece_a",
        "fecha_vigencia_inicio",
        "fecha_vigencia_fin",
        "indefinido",
        "id_usuario_created",
    ];
    
    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }
    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
