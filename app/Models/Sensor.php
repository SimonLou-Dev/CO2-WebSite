<?php

namespace App\Models;

use App\Casts\hex;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @OA\Schema (
 *     schema="Sensor",
 *     title="schema of sensor",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="last_message", type="string"),
 *     @OA\Property(property="created_by", type="string"),
 *     @OA\Property(property="room_id", type="int"),
 *      @OA\Property(property="id_hex", type="string"),
 *      @OA\Property(property="created_at", type="string"),
 *      @OA\Property(property="updated_at", type="string"),
 *      @OA\Property(property="deleted_at", type="string")
 * )
 *
 *
 */
class Sensor extends Model
{
    use HasFactory;

    protected $fillable = ["room_id", "created_by", "device_addr"];



    protected $casts = [
        'last_message' => 'datetime',
    ];

    protected $appends = ["id_hex"];

    /**

     * Determine  full name of user

     *

     * @return \Illuminate\Database\Eloquent\Casts\Attribute

     */
    public function getIdHexAttribute():string
    {
        return $this->intToHex($this->id);
    }

    private function intToHex(int $value):string
    {
        $value = dechex($value);
        $i = 0;
        while (\Str::length($value) < 4 && $i <10 ){
            $i++;
            $value = "0".$value;
        }

        return "0x".$value;
    }

    public function getRoom()
    {
        return $this->belongsTo(Room::class, "room_id");
    }







}
