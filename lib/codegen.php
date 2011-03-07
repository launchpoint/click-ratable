<?

function codegen_ratable_get_rating_thread($s, $name)
{
  $rt = RatingThread::find_or_create_by( array(
    'conditions'=>array("object_id = ? and object_type = ? and name = ?", $s->id, $s->klass, $name),
    'attributes'=>array(
      'name'=>$name,
      'object_type'=>$s->klass,
      'object_id'=>$s->id
    )
  ));
  return $rt;
}

function codegen_ratable_get_next_unrated($u, $name, $addl_conditions, $t, $table_name)
{
  $ip = getenv("REMOTE_ADDR");
  if ($u->id)
  {
    $conditions = "r.user_id = $u->id";
  } else {
    $conditions = "r.ip_address = '$ip'";
  }
  
  $params = array(
    'conditions'=>array(
      "id not in (select s.id from $table_name s join rating_threads rt on rt.name='$name' and rt.object_id = s.id and rt.object_type='$t' join ratings r on r.rating_thread_id = rt.id and $conditions)"
    ),
    'load'=>array('user'=>'profile', 'picture')
  );
  if ($addl_conditions)
  {
    $params = ActiveRecord::add_conditions($params, $addl_conditions);
  }
    
  $obj = eval("return $t::find(\$params);");
  return $obj;
}