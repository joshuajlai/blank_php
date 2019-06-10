<?php

namespace App\Models;

class Base
{
    protected function generateWhere($properties)
    {
        $query = '';
        if (count($properties)) {
            $query .= "where ";
        }

        $query_parts = [];
        foreach ($properties as $property => $value) {
            $query_parts[] = "{$property} = ?";
        }
        $query .= implode(" AND ", $query_parts);

        return " {$query} ";
    }
}
