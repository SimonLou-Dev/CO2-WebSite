<?php


namespace App\Facade\Accessor;



use App\Facade\ViteAssetLoader;
use Illuminate\Support\Facades\Facade;

class ViteFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return ViteAssetLoader::class;
    }

}
