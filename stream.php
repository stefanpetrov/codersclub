<?php
header('Content-Type: application/xml; charset=utf-8');

$songs = array(
  array(
    'title' => 'Blues Brothers - The Blues Don\'t Bother Me ( Matt "Guitar" Murphy)',
    'album' => 'Blues Brothers 2000 OST',
    'genre' => 'R&amp;B',
    'duration' => '3:37',
  ),
  array(
    'title' => 'Blues Brothers - Riot In Cell Block Number Nine',
    'album' => 'Blues Brothers 2000 OST',
    'genre' => 'R&amp;B',
    'duration' => '3:30',
  ),
  array(
    'title' => 'Blues Brothers - Almost',
    'album' => 'Blues Brothers 2000 OST',
    'genre' => 'R&amp;B',
    'duration' => '2:50',
  ),
  array(
    'title' => 'The Commitments - Mustang Sally',
    'album' => 'The Commitments',
    'genre' => 'R&amp;B',
    'duration' => '4:06',
  ),
  array(
    'title' => 'Stealers Wheel - Stuck In The Middle With You',
    'album' => 'Album one',
    'genre' => 'Rock',
    'duration' => '3:37',
  ),
  array(
    'title' => 'The Beatles - Come Together',
    'album' => 'Album two',
    'genre' => 'Rock',
    'duration' => '4:14',
  ),
);

print '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
$song = array_rand($songs);
?>
<stream>
  <title><?php print $songs[$song]['title']; ?></title>
  <album><?php print $songs[$song]['album']; ?></album>
  <genre><?php print $songs[$song]['genre']; ?></genre>
  <duration><?php print $songs[$song]['duration']; ?></duration>
  <next><?php print rand(1,2)?>:<?php print rand(0, 60)?></next>
</stream>



