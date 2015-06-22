<?php

/**
 * Class codersClub
 */
class codersClub {
  /**
   * @var string | Path to source xml
   */
  public $source;
  /**
   * @var array | Collect messages from script
   */
  public $message;
  /**
   * @var object | Cache XML
   */
  public $xml = NULL;
  /**
   * @var Object | DataBase object
   */
  private $db;
  /**
   * @var Boolean | Indicates ajax
   */
  private $ajax;

  /**
   * @param $source Path to source xml
   * @param $host   DB host
   * @param $db     DB name
   * @param $usr    DB username
   * @param $pass   DB password
   */
  function __construct($source, $host, $db, $usr, $pass) {
    $this->source = $source;
    $this->ajax = isset($_GET['ajax']);
    $this->verifyPHPVersion();
    $this->initDB($host, $db, $usr, $pass);
    $this->install();
    $this->parseXML();

    $this->jumpArround();
  }

  function jumpArround() {
    $result = NULL;
    if (isset($_GET['key'])) {
      switch ($_GET['key']) {
        case 'current': // return current song
          $result = $this->current();
          $this->deliver($result);
          break;
        case 'report': // generate report
          $result = $this->report();
          $this->deliver($result);
          break;
        case 'validatePHP': //validate PHP version
          if ($verifyVersion = $this->verifyPHPVersion()) {
            $result = array('error' => $verifyVersion);
          }
          $this->deliver($result);
          break;
        case 'save':
          $this->save();
          break;
      }
    }

  }

  function deliver($data = array()) {
    $result = array(
      'data' => $data
    );
    if (isset($this->message['error'])) {
      $result['error'] = $this->message['error'];
    }
    print json_encode($result);
  }

  /**
   * @param $host   DB host
   * @param $db     DB name
   * @param $usr    DB username
   * @param $pass   DB password
   */
  function initDB($host, $db, $usr, $pass) {
    include_once "MysqliDb.php";
    $this->db = new MysqliDb($host, $db, $usr, $pass);
  }

  /**
   * Parse XML from source file
   */
  function parseXML() {
    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, $this->source);
    curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

    $file = curl_exec($curlSession);
    curl_close($curlSession);

    if ($file !== FALSE) {
      $this->xml = simplexml_load_string($file);
    } else {
      $this->message('error', 'Parse XML: Unable to open source file');
    }
  }

  /**
   * Save song to DB
   */
  function save() {
    if (is_null($this->xml)) {
      $this->message('error', 'Save: Invalid data');
     return FALSE;
    }
    $value = explode("-", (string) $this->xml->title);
    $artist = array_shift($value);
    $artist = trim($artist);
    $title = implode("-", $value);
    $title = trim($title);
    $album = (string) $this->xml->album;
    $genre = (string) $this->xml->genre;
    $duration = (string) $this->xml->duration;

    $data = array(
      'title'     => $title,
      'artist'    => $artist,
      'timestamp' => time(),
      'album'     => $album,
      'genre'     => $genre,
      'duration'  => $duration,
    );
    $this->db->insert('song', $data);
  }

  /**
   * @return array Return currently played song
   */
  function current() {
    if (is_null($this->xml)) {
      $this->message('error', 'Current song: Invalid data');
      return FALSE;
    }
    return array(
      'title'     => (string) $this->xml->title,
      'timestamp' => time(),
      'album'     => (string) $this->xml->album,
      'genre'     => (string) $this->xml->genre,
      'duration'  => (string) $this->xml->duration,
      'next'      => (string) $this->xml->next,
    );
  }

  /**
   * @return array Generate report from DB
   */
  function report() {
    if (is_null($this->xml)) {
      $this->message('error', 'Report: Invalid data');
      return FALSE;
    }
    $dateFrom = $this->get('dateFrom');
    $dateTo = $this->get('dateTo');
    return array(
      'popularArtist' => $this->getMostPopularArtist($dateFrom, $dateTo),
      'popularSong'   => $this->getMostPopularSong($dateFrom, $dateTo),
      'popularGenre'  => $this->getMostPopularGenre($dateFrom, $dateTo),
      'topGenre'      => $this->getTopGenreBySong($dateFrom, $dateTo),
      'longest'       => $this->getLongestSong($dateFrom, $dateTo),
      'shortest'      => $this->getShortestSong($dateFrom, $dateTo),
    );
  }

  /**
   * @return string Generate message if PHP version is not compatible
   */
  function verifyPHPVersion() {
    $php530 = version_compare(PHP_VERSION, '5.3.0', '>=');
    if (!$php530) {
      return 'This script requires at least PHP version 5.3.0';
    }
  }

  /**
   * @param $key string Name of the variable from $_GET
   * @return string Sanitized value
   */
  function get($key) {
    $value = FALSE;

    if (isset($_GET[$key])) {
      $value = trim($_GET[$key]);
      $value = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING, FILTER_SANITIZE_NUMBER_INT);
    }

    return $value;
  }

  /**
   * @param $dateFrom Start date
   * @param $dateTo   End date
   * @return mixed    Result
   */
  private function getMostPopularArtist($dateFrom, $dateTo) {
    $this->db->where('timestamp', strtotime($dateFrom), ">=");
    $this->db->where('timestamp', strtotime($dateTo), "<=");
    $this->db->orderBy('cnt', 'desc');
    $this->db->groupBy('artist');

    $cols = array(
      'artist', 'count(*) as cnt'
    );
    $result = 'Nothing found';
    if ($res = $this->db->get('song', array(0, 1), $cols)) {
      $result = $res[0]['artist'];
    }

    return $result;
  }

  /**
   * @param $dateFrom Start date
   * @param $dateTo   End date
   * @return mixed    Result
   */
  private function getMostPopularSong($dateFrom, $dateTo) {
    $this->db->where('timestamp', strtotime($dateFrom), ">=");
    $this->db->where('timestamp', strtotime($dateTo), "<=");
    $this->db->orderBy('cnt', 'desc');
    $this->db->groupBy('title');

    $cols = array(
      'title', 'count(*) as cnt'
    );
    $result = 'Nothing found';
    if ($res = $this->db->get('song', array(0, 1), $cols)) {
      $result = $res[0]['title'];
    }

    return $result;
  }

  /**
   * @param $dateFrom Start date
   * @param $dateTo   End date
   * @return mixed    Result
   */
  private function getMostPopularGenre($dateFrom, $dateTo) {
    $this->db->where('timestamp', strtotime($dateFrom), ">=");
    $this->db->where('timestamp', strtotime($dateTo), "<=");
    $this->db->orderBy('cnt', 'desc');
    $this->db->groupBy('genre');

    $cols = array(
      'genre', 'count(*) as cnt'
    );
    $result = 'Nothing found';
    if ($res = $this->db->get('song', array(0, 1), $cols)) {
      $result = $res[0]['genre'];
    }

    return $result;
  }

  /**
   * @param $dateFrom Start date
   * @param $dateTo   End date
   * @return mixed    Result
   */
  private function getTopGenreBySong($dateFrom, $dateTo) {
    $this->db->where('timestamp', strtotime($dateFrom), ">=");
    $this->db->where('timestamp', strtotime($dateTo), "<=");
    $this->db->orderBy('cnt', 'desc');

    $cols = array(
      'distinct(title)', 'count(*) as cnt'
    );
    $result = 'Nothing found';
    $res = $this->db->get('song', array(0, 1), $cols);
    if ($res[0]['cnt'] > 0) {
      $result = $res[0]['title'];
    }
    return $result;
  }

  /**
   * @param $dateFrom Start date
   * @param $dateTo   End date
   * @return mixed    Result
   */
  private function getLongestSong($dateFrom, $dateTo) {
    $this->db->where('timestamp', strtotime($dateFrom), ">=");
    $this->db->where('timestamp', strtotime($dateTo), "<=");
    $this->db->orderBy('duration', 'desc');
    $cols = array(
      'title'
    );
    $result = 'Nothing found';
    if ($res = $this->db->get('song', array(0, 1), $cols)) {
      $result = $res[0]['title'];
    }

    return $result;
  }

  /**
   * @param $dateFrom Start date
   * @param $dateTo   End date
   * @return mixed    Result
   */
  private function getShortestSong($dateFrom, $dateTo) {
    $this->db->where('timestamp', strtotime($dateFrom), ">=");
    $this->db->where('timestamp', strtotime($dateTo), "<=");
    $this->db->orderBy('duration', 'asc');
    $cols = array(
      'title'
    );
    $result = 'Nothing found';
    if ($res = $this->db->get('song', array(0, 1), $cols)) {
      $result = $res[0]['title'];
    }

    return $result;
  }

  function message($key, $message) {
    $this->message[$key][] = $message;
  }

  function printMessages() {
    return $this->message;
  }

  private function install() {
    $bale = $this->db->rawQuery("SHOW TABLES LIKE 'song'");
    if (!isset($bale[0])) {
      $sql = "CREATE TABLE IF NOT EXISTS song (
  id int(11) NOT NULL AUTO_INCREMENT,
  timestamp int(11) NOT NULL,
  title varchar(255) CHARACTER SET utf8 NOT NULL,
  artist varchar(255) CHARACTER SET utf8 NOT NULL,
  album varchar(255) CHARACTER SET utf8 NOT NULL,
  genre varchar(255) CHARACTER SET utf8 NOT NULL,
  duration varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
      $result = $this->db->rawQuery($sql);
    }
  }
}


$codersClub = new codersClub('XML_SOURCE', 'DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_NAME');