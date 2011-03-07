<?
$rt = RatingThread::find_by_id($params['id']);
$rt->delete_rating( (logged_in() ? $current_user : null) );
if(array_key_exists('redirect', $params))
{
  redirect_to($params['redirect']);
}