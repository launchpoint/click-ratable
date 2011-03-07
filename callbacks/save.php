<?
$rt = RatingThread::find_by_id($params['id']);
$rt->add_rating((logged_in() ? $current_user : null), $params['rating']);
if(array_key_exists('redirect', $params))
{
  redirect_to($params['redirect']);
}