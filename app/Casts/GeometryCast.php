<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GeometryCast implements CastsAttributes
{
    /**
     * Cast the given value from the database.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // We assume the conversion to a readable format (WKT)
        // has already been done at the query level (e.g., using selectRaw).
        // The value is already a string, so we return it as is.
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // When setting the value, convert the readable coordinate string back into a geometry object
        // for the database.
        if (is_string($value) && str_contains($value, ',')) {
            $coords = explode(',', $value);
            return DB::raw("ST_PointFromText('POINT(" . trim($coords[0]) . " " . trim($coords[1]) . ")')");
        }
        return null;
    }

    /**
     * Get the raw expression for selecting the geometry column as text.
     *
     * @param string $column
     * @return \Illuminate\Database\Query\Expression
     */
    public static function selectExpression(string $column)
    {
        return DB::raw("ST_AsText({$column}) as {$column}");
    }
}
