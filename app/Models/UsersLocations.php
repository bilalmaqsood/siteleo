<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersLocations extends Model
{
    protected $table = 'users_locations';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getProvinces(){
        return [
            "A Coruña",
            "Álava",
            "Albacete",
            "Alicante",
            "Almería",
            "Asturias",
            "Ávila",
            "Badajoz",
            "Baleares",
            "Barcelona",
            "Bizkaia",
            "Burgos",
            "Cáceres",
            "Cádiz",
            "Cantabria",
            "Castellón",
            "Ceuta",
            "Ciudad Real",
            "Córdoba",
            "Cuenca",
            "Gipuzkoa",
            "Girona",
            "Granada",
            "Guadalajara",
            "Huelva",
            "Huesca",
            "Jaén",
            "La Rioja",
            "Las Palmas",
            "León",
            "Lleida",
            "Lugo",
            "Madrid",
            "Málaga",
            "Melilla",
            "Murcia",
            "Navarra",
            "Ourense",
            "Palencia",
            "Pontevedra",
            "Salamanca",
            "Santa Cruz de Tenerife",
            "Segovia",
            "Sevilla",
            "Soria",
            "Tarragona",
            "Teruel",
            "Toledo",
            "Valencia",
            "Valladolid",
            "Zamora",
            "Zaragoza",
        ];
    }

    public static function getPoblacion(){
        return [
           "Andalucía",
            "Aragón",
            "Principado de Asturias",
            "Islas Baleares",
            "Canarias",
            "Cantabria",
            "Castilla y León",
            "Castilla-La Mancha",
            "Cataluña",
            "Comunidad Valenciana",
            "Extremadura",
            "Galicia",
            "Comunidad de Madrid",
            "Región de Murcia",
            "Comunidad Foral de Navarra",
            "País Vasco",
            "La Rioja",
            "Ceuta",
            "Melilla"
        ];
    }
}
