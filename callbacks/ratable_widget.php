<?
if (!isset($style)) $style='updown';
if (!isset($max_score)) $max_score=1;
event($style, array('rt'=>$rt, 'max_score'=>$max_score));