<?php

require 'Conectar.php';



require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim();

$app->post('/login', 'Login');
$app->get('/list', 'Lists');
$app->get('/listvin/:vin', 'ListVIN');
$app->get('/badge', 'Badge');
$app->get('/', 'Index');

/*
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});
*/
$app->run();

$app->error(function (\Exception $e) use ($app) {
    var_dump($e);

});

function Login() {

    $conex_ = new Conectar();

    $conex = $conex_->conexion();

	$app = \Slim\Slim::getInstance();

	$request = $app->request();

	//$token = $request->headers('Authorization');

    //$charset = $app->request->headers->get('ACCEPT_CHARSET');

	$user = $request->post('user');
	
	$pass = $request->post('pass');

	/**** estructara de datos */

	$sql_user = "select  * from users where email ='%s' and password = '%s'";

    $sql_user_ = sprintf($sql_user, $user, $pass );

    $stmt = $conex->query($sql_user_);

    if($stmt) {
        if ($user = $stmt->fetch_object()) {

            $usersf = Array("Err" => 0, "Msg" => "Datos Correctos", Array(
                "user_id" => $user->user_id,
                "user_nombre" => $user->user_nombre,
                "user_apellido" => $user->user_apellido,
                "user_cargo" => $user->user_cargo,
                "user_estado" => $user->user_estado));

        } else {
            $usersf = Array("Err" => 1, "Msg" => "Datos Incorrectos");
        }
    }else{
        $usersf = Array("Err" => 2, "Msg" => "Datos Incorrectos.");
    }


 
	echo json_encode($usersf);
	
	
}

function ListVIN($vin){

    $conex_ = new Conectar();

    $conex = $conex_->conexion();

    $sql_vin = "select * from vins where vin_codigo ='%s'";

    $sql_vin_ = sprintf($sql_vin, $vin);

    $stmt = $conex->query($sql_vin_);

    if($stmt) {
        if ($vins = $stmt->fetch_object()) {

            $vinsf = Array("Err" => 0, "Msg" => "Datos Correctos", "items"=>Array(
                "vin"=>$vins->vin_codigo,
                "modelo"=>$vins->vin_modelo,
                "marca"=>$vins->vin_marca,
                "posicion"=>"OFICINA009",
                "destino"=>"OFICINA",
                "bandera"=>"green",
                "ico"=>"DyP", "check"=>"false"
                ));

        } else {
            $vinsf = Array("Err" => 1, "Msg" => "Datos Incorrectos");
        }
    }else{
        $vinsf = Array("Err" => 2, "Msg" => "Datos Incorrectos.");
    }

    echo json_encode($vinsf);

}

function Index(){
	echo "Api Activo";
}

function Lists() {
sleep(1);
    $list=Array(
        Array("vin"=>"10101019010",
        "modelo"=>"MITSUBISHI",
        "marca"=>"MONTERO",
        "destino"=>"OFICINA",
        "posicion"=>"OFICINA010",
        "bandera"=>"red",
        "ico"=>"picking",
        "check"=>"true"),
        Array("vin"=>"10101019011",
            "modelo"=>"MITSUBISHI1",
            "marca"=>"MONTERO",
            "destino"=>"OFICINA",
            "posicion"=>"OFICINA11",
            "bandera"=>"green",
            "ico"=>"DyP",
            "check"=>"false"),
        Array("vin"=>"10101019012",
            "modelo"=>"MITSUBISHI1",
            "marca"=>"MONTERO",
            "destino"=>"OFICINA",
            "posicion"=>"OFICINA009",
            "bandera"=>"yellow",
            "ico"=>"car-wash",
            "check"=>"false"),
        Array("vin"=>"10101019013",
            "modelo"=>"MITSUBISHI13",
            "marca"=>"MONTERO",
            "destino"=>"OFICINA",
            "posicion"=>"OFICINA013",
            "bandera"=>"green",
            "ico"=>"change",
            "check"=>"false")
    );



    echo json_encode(Array("Err"=>0,"listData"=>$list));

}



function Badge() {
    sleep(1);

    echo json_encode(Array("Err"=>0,"badgeData"=>Array("list"=>2, "car"=>3, "boat"=>2)));

}


?>
