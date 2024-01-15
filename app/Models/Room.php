<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @OA\Schema (
 *     schema="Room",
 *     title="schema of room",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="created_at", type="string"),
 *     @OA\Property(property="updated_at", type="string")
 *
 * )
 *
 *
 */
class Room extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ["name"];
}
