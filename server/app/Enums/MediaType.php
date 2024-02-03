<?php

namespace App\Enums;

enum MediaType: string
{
    case Movie = "movie";
    case TV = "tv";

    /**
     * @return string
     */
    public function label(): string
    {
        return match($this)
        {
            MediaType::Movie => '映画',
            MediaType::TV  => 'TV',
        };
    }
}