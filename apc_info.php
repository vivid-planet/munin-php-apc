<?php
/**
 * TODO: File header.
 * TODO: Code comments.
 */

if(function_exists("apc_cache_info") && function_exists("apc_sma_info")) {

  $time = time();

  $mem = apc_sma_info();
  $mem_size = $mem['num_seg']*$mem['seg_size'];
  $mem_avail= $mem['avail_mem'];
  $mem_used = $mem_size-$mem_avail;

  // Some code taken from the file apc.php by The PHP Group.
  $nseg = $freeseg = $fragsize = $freetotal = 0;
  for($i=0; $i<$mem['num_seg']; $i++) {
    $ptr = 0;
    foreach($mem['block_lists'][$i] as $block) {
      if ($block['offset'] != $ptr) {
        ++$nseg;
      }
      $ptr = $block['offset'] + $block['size'];
      // Only consider blocks <5M for the fragmentation %
      if($block['size']<(5*1024*1024)) $fragsize+=$block['size'];
      $freetotal+=$block['size'];
    }
    $freeseg += count($mem['block_lists'][$i]);
  }

  if ($freeseg < 2) {
    $fragsize = 0;
    $freeseg = 0;
  }

////Optcode (file) Cache
  $cache_mode = 'opmode';
  $cache=@apc_cache_info($cache_mode, true);

  // Item hits, misses and inserts
  $hits = $cache['num_hits'];
  $misses = $cache['num_misses'];
  $inserts = $cache['num_inserts'];

  //
  $req_rate = ($cache['num_hits']+$cache['num_misses'])/($time-$cache['start_time']);
  $hit_rate = ($cache['num_hits'])/($time-$cache['start_time']); // Number of entries in cache $number_entries = $cache['num_entries'];
  $miss_rate = ($cache['num_misses'])/($time-$cache['start_time']); // Total number of cache purges $purges = $cache['expunges'];
  $insert_rate = ($cache['num_inserts'])/($time-$cache['start_time']);

  // Number of entries in cache
  $number_entries = $cache['num_entries'];

  // Total number of cache purges
  $purges = $cache['expunges'];

  $optcode_mem_size = $cache['mem_size'];

  //apc_clear_cache($cache_mode);

////User Cache
  $cache_mode = 'user';
  $cache=@apc_cache_info($cache_mode, true);

  // Item hits, misses and inserts
  $user_hits = $cache['num_hits'];
  $user_misses = $cache['num_misses'];
  $user_inserts = $cache['num_inserts'];

  //
  $user_req_rate = ($cache['num_hits']+$cache['num_misses'])/($time-$cache['start_time']);
  $user_hit_rate = ($cache['num_hits'])/($time-$cache['start_time']); // Number of entries in cache $number_entries = $cache['num_entries'];
  $user_miss_rate = ($cache['num_misses'])/($time-$cache['start_time']); // Total number of cache purges $purges = $cache['expunges'];
  $user_insert_rate = ($cache['num_inserts'])/($time-$cache['start_time']);

  // Number of entries in cache
  $user_number_entries = $cache['num_entries'];

  // Total number of cache purges
  $user_purges = $cache['expunges'];

  $user_mem_size = $cache['mem_size'];

  $out = array(
    'size: ' . $mem_size,
    'used: ' . $mem_used,
    'free: ' . ($mem_avail - $fragsize),

    'hits: ' . sprintf("%.2f", $hits * 100 / ($hits + $misses)),
    'misses: ' . sprintf("%.2f", $misses * 100 / ($hits + $misses)),
    'request_rate: ' . sprintf("%.2f", $req_rate),
    'hit_rate: ' . sprintf("%.2f", $hit_rate),
    'miss_rate: ' . sprintf("%.2f", $miss_rate),
    'insert_rate: ' . sprintf("%.2f", $insert_rate),
    'entries: ' . $number_entries,
    'inserts: ' . $inserts,
    'purges: ' . $purges,

  // TODO: Delete
    'purge_rate: ' . sprintf("%.2f", (100 - ($number_entries / $inserts) * 100)),
  // TODO: Delete
    'fragment_percentage: ' . sprintf("%.2f", ($fragsize/$mem_avail)*100),
    'fragmented: ' . sprintf("%.2f", $fragsize),
    'fragment_segments: ' . $freeseg,

    'optcode_size: ' . $optcode_mem_size,
    'user_size: ' . $user_mem_size,

    'user_hits: ' . sprintf("%.2f", ($user_hits + $user_misses) ? ($user_hits * 100 / ($user_hits + $user_misses)) : 0),
    'user_misses: ' . sprintf("%.2f", ($user_hits + $user_misses) ? ($user_misses * 100 / ($user_hits + $user_misses)) : 0),
    'user_request_rate: ' . sprintf("%.2f", $user_req_rate),
    'user_hit_rate: ' . sprintf("%.2f", $user_hit_rate),
    'user_miss_rate: ' . sprintf("%.2f", $user_miss_rate),
    'user_insert_rate: ' . sprintf("%.2f", $user_insert_rate),
    'user_entries: ' . $user_number_entries,
    'user_inserts: ' . $user_inserts,
    'user_purges: ' . $user_purges,

  // TODO: Delete
    'user_purge_rate: ' . sprintf("%.2f", $user_inserts ? (100 - ($user_number_entries / $user_inserts) * 100) : 0),
  );
}
else {
  $out = array('APC-not-installed');
}
echo implode(' ', $out);