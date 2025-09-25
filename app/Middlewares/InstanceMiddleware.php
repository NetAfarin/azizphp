<?php
namespace App\Middlewares;


use App\Core\Request;
use App\Models\Salon;

class InstanceMiddleware
{
    public function handle($request, $next)
    {
        $instanceName = $request->segment(1);
        if ($instanceName=="admin"){
            return $next($request);
        }
        $instance = Salon::query()->where('username','=', $instanceName)->first();
        //TODO az in middleware be dorosti bayad estedfade konam, felan estefadeye khasi nadare
        if (!$instanceName){
            define('SALON_ID', "");
        }else if (!$instance) {
//            define('SALON_ID', $instanceName);
            redirect('/404');
            exit();
        }else{
            define('SALON_ID', $instance->username);
        }


        return $next($request);
    }
}

