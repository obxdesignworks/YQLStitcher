<?php
// get the current file directory
function get_file_dir() {
  global $argv;
  return realpath($argv[0]);
}
$cwd = get_file_dir();
// require the YQLStitcher class
require_once $cwd . '/yqlstitcher.class.php';
// cache folder
$cache = $cwd . '/cache';
// time in seconds to retain the cache file (defaults to 1 hour)
$timeout = 60;
//feeds we want to stitch together
$feeds = array(
  "http://feeds.delicious.com/v2/rss/tag/webdesign?count=15",
  "http://www.alistapart.com/site/rss"
);
// return data format
$format = 'xml';
// the YQL query to run
$query = "select channel.title,channel.link,channel.item.title,channel.item.description,channel.item.link from xml";

// new YQLStitcher instance
$yql = new YQLStitcher($cache, $timeout, $feeds, $format, $query, 15, 'channel.item', true);

// grab the newsfeed items
$items = $yql->get_items();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Testing YQLStitcher</title>
</head>
<body>
  <h1>Testing YQLStitcher</h1>
  <hr />
<?php
// loop over and display the newsfeed items
foreach ($items as $item) {
  echo '<h2>Link: <a href="' . $item->channel->item->link . '">' . $item->channel->item->title . '</a></h2>';
  $datestamp  = 'Published on: ';
  $datestamp .= defined($item->channel->item->pubDate) ? date('Y-m-d', strtotime($item->channel->item->pubDate)) . ' | ' : '<em>unavailable</em> | ';
  echo '<p><small>' . $datestamp . 'Source: <a href="' . $item->channel->link . '">' . $item->channel->title . '</a></small></p>';
  echo $item->channel->item->description;
}
?>
<hr />
<p><small>&#169; <?php echo date('Y'); ?> Outer Banks Design Works.</small></p>
</body>
</html>