<?php

namespace App\Models;
use App\Core\Model;

class UserType extends Model
{
    protected string $table = 'user_type_table';

    protected array $fillable = ['title', 'en_title'];

}