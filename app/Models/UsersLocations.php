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
            "Almerнa",
            "A Coruсa",
            "Бlava",
            "Albacete",
            "Alicante",
            "Asturias",
            "Бvila",
            "Badajoz",
            "Baleares",
            "Barcelona",
            "Bizkaia",
            "Burgos",
            "Cбceres",
            "Cбdiz",
            "Cantabria",
            "Castellуn",
            "Ceuta",
            "Ciudad Real",
            "Cуrdoba",
            "Cuenca",
            "Gipuzkoa",
            "Girona",
            "Granada",
            "Guadalajara",
            "Huelva",
            "Huesca",
            "Jaйn",
            "La Rioja",
            "Las Palmas",
            "Leуn",
            "Lleida",
            "Lugo",
            "Madrid",
            "Mбlaga",
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
           "Andalucнa",
            "Aragуn",
            "Principado de Asturias",
            "Islas Baleares",
            "Canarias",
            "Cantabria",
            "Castilla y Leуn",
            "Castilla-La Mancha",
            "Cataluсa",
            "Comunidad Valenciana",
            "Extremadura",
            "Galicia",
            "Comunidad de Madrid",
            "Regiуn de Murcia",
            "Comunidad Foral de Navarra",
            "Paнs Vasco",
            "La Rioja",
            "Ceuta",
            "Melilla"
        ];
    }
}
