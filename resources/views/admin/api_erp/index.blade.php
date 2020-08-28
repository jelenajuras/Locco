@php
include('../phpxmlrpc-4.4.1/lib/xmlrpc.inc');

echo "Authenticated User ID: <b>".connect()."</b>";

function connect() {
	$user = 'employee_portal_admin';
	$password = 'duplico1234';
	$dbname = 'duplico_test';
	$server_url = 'https://test.odoo.eur.hr:8016/xmlrpc/';
  
	if(isset($_COOKIE["user_id"]) == true){
		if($_COOKIE["user_id"]>0){
			return $_COOKIE["user_id"];
		}
	}
	
	$sock = new xmlrpc_client($server_url."common");
    $sock->setSSLVerifyPeer(0);
	$msg = new xmlrpcmsg('login');
	$msg->addParam(new xmlrpcval($dbname, "string"));
	$msg->addParam(new xmlrpcval($user, "string"));
	$msg->addParam(new xmlrpcval($password, "string"));
    $resp = $sock->send($msg);

	$val = $resp->value();
	if(! is_int($val)){
        $id = $val->scalarval();
        setcookie("user_id", $id, time() + 3600);
        if($id > 0){
            return $id;
        } 
        else{
            return -1;
        }
	} 
}

  /**
   * $client = xml-rpc handler
   * $relation = name of the relation ex: res.partner
   * $attribute = name of the attribute ex:code
   * $operator = search term operator ex: ilike, =, !=
   * $key=search for
  */

  function search($client,$relation,$attribute,$operator,$keys) {
      $user = 'employee_portal_admin';
      $password = 'duplico1234';
      $userId = -1;
      $dbname = 'duplico_test';
      $server_url = 'https://cloud.odoo.eur.hr:8170/xmlrpc/';

      $key = array(new xmlrpcval(array(new xmlrpcval($attribute , "string"),
              new xmlrpcval($operator,"string"),
              new xmlrpcval($keys,"string")),"array"),
          );

      if($userId<=0) {
          connect();
      }

      $msg = new xmlrpcmsg('execute');
      $msg->addParam(new xmlrpcval($dbname, "string"));
      $msg->addParam(new xmlrpcval($userId, "int"));
      $msg->addParam(new xmlrpcval($password, "string"));
      $msg->addParam(new xmlrpcval($relation, "string"));
      $msg->addParam(new xmlrpcval("search", "string"));
      $msg->addParam(new xmlrpcval($key, "array"));

      $resp = $client->send($msg);
      $val = $resp->value();
      $ids = $val->scalarval();

      return $ids;
  }

  /**
   * $client = xml-rpc handler
   * $relation = name of the relation ex: res.partner
   * $attribute = name of the attribute ex:code
   * $operator = search term operator ex: ilike, =, !=
   * $id = id of the record to be updated
   * $data = data to be updated
  */

  function write($client, $relation, $attribute, $operator, $data, $id) {
      $user = 'employee_portal_admin';
      $password = 'duplico1234';
      $userId = -1;
      $dbname = 'duplico_test';
      $server_url = 'https://cloud.odoo.eur.hr:8170/xmlrpc/';

      $id_val = array();
      $id_val[0] = new xmlrpcval($id, "int");

      if($userId<=0) {
          connect();
      }

      $msg = new xmlrpcmsg('execute');
      $msg->addParam(new xmlrpcval($dbname, "string"));
      $msg->addParam(new xmlrpcval($userId, "int"));
      $msg->addParam(new xmlrpcval($password, "string"));
      $msg->addParam(new xmlrpcval($relation, "string"));
      $msg->addParam(new xmlrpcval("write", "string"));
      $msg->addParam(new xmlrpcval($id, "array"));
      $msg->addParam(new xmlrpcval($data, "struct"));

      $resp = $client->send($msg);
      $val = $resp->value();
      $record = $val->scalarval();

      return $record;
  }

  
/* create */
/*     $arrayVal = array(
        'name'=>new xmlrpcval('Fabien Pinckaers', "string") ,
        'vat'=>new xmlrpcval('BE477472701' , "string")
    );
    $client = new xmlrpc_client('https://cloud.odoo.eur.hr:8170/xmlrpc/';

    $msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval("3", "int"));
    $msg->addParam(new xmlrpcval("demo", "string"));
    $msg->addParam(new xmlrpcval("res.partner", "string"));
    $msg->addParam(new xmlrpcval("create", "string"));
    $msg->addParam(new xmlrpcval($arrayVal, "struct"));
    $resp = $client->send($msg); */
  /*   dd( $resp ); */
    /* dd( $msg );

    if ($resp->faultCode())

        echo 'Error: '.$resp->faultString();

    else

    echo 'Partner '.$resp->value()->scalarval().' created !'; */


@endphp
