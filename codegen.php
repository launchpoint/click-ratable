<?

foreach($models as $model_klass)
{
  $t = singularize(tableize($model_klass));
  $table_name = eval("return $model_klass::\$table_name;");
  if ($t=='rating' || $t=='rating_thread') continue;
  $code = <<<PHP
function {$t}_rating_threads__d(\$s, \$name)
{
  return codegen_ratable_get_rating_thread(\$s, \$name);
}

function {$t}_get_rating_thread__d(\$o)
{
  return \$o->rating_threads('default');
}

function user_next_unrated_{$t}(\$u, \$name, \$addl_conditions=null)
{
  return codegen_ratable_get_next_unrated(\$u, \$name, \$addl_conditions, '$t', '$table_name');
}

PHP;
  $codegen[] = $code;
}
