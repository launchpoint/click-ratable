<?

function rating_thread_ratable($rt, $attrs)
{
  $attrs['rt'] = $rt;
  event('ratable_widget', $attrs);
}

function rating_thread_updown_ratable($rt)
{
  $rt->ratable(array('style'=>'updown'));
}

function rating_thread_abuse_ratable($rt)
{
  $rt->ratable(array('style'=>'abuse'));
}


function rating_thread_stars_ratable($rt, $max=5)
{
  $rt->ratable(array('style'=>'stars', 'max_score'=>$max));
}


function rating_thread_add_rating__d($rt, $user, $value)
{
  $rating = $rt->rating_for($user);
  $rating->value = $value;
  $rating->save();
  return $rating;
}

function rating_thread_delete_rating__d($rt, $user)
{
  $rating = $rt->rating_for($user);
  if (!$rating->is_new) $rating->delete();
}

function rating_thread_get_total_vote_count($rt)
{
  return $rt->upvote_count + $rt->downvote_count;
}

function rating_thread_get_vote_count($rt)
{
  return $rt->upvote_count - $rt->downvote_count;
}

function rating_thread_get_upvote_count__d($rt)
{
  return $rt->count_upvotes();
}

function rating_thread_get_downvote_count__d($rt)
{
  return $rt->count_downvotes();
}


function rating_thread_count_upvotes__d($rt, $default=1)
{
  return $rt->count_votes($default, '>=');
}

function rating_thread_count_downvotes__d($rt, $default=1)
{
  return $rt->count_votes($default, '<');
}

function rating_thread_count_votes__d($rt, $default, $dir)
{
  $sql = "select count(id) as c from ratings where rating_thread_id = $rt->id and value $dir $default";
  $res = query_assoc($sql);
  return $res[0]['c'];
}

function rating_thread_get_score__d($rt)
{
  $sql = "select avg(value) as score from ratings where rating_thread_id = $rt->id";
  $res = query_assoc($sql);
  $val = $res[0]['score'];
  if ($val==null) $val=0;
  return number_format($val,2);
}

function rating_thread_get_stars__d($rt)
{
  $width = ceil(16 * $rt->score);
  $pad = 80-$width;
  $url = RATABLE_VPATH."/assets/images/star.gif";
  return "<span style='min-height: 16px; padding-right: {$pad}px; background: transparent url({$url}) 0 0px repeat-x'><span style='min-height: 16px; padding-left: {$width}px; background: transparent url({$url}) 0 -32px repeat-x;'></span></span>";
}

function rating_thread_rating_for__d($rt, $user=null)
{
  $ip = getenv("REMOTE_ADDR") ;
  if ($user && $user->id)
  {
    $conditions = "user_id = $user->id";
  } else {
    $conditions = "ip_address = '$ip'";
  }

  $rating = Rating::find_or_new_by( array(
    'conditions'=>array("rating_thread_id = ? and !", $rt->id, $conditions),
    'attributes'=>array(
      'rating_thread_id'=>$rt->id,
      'user_id'=>($user) ? $user->id : null,
      'ip_address'=>$ip,
      'value'=>null
    )
  ));
  
  return $rating;
}