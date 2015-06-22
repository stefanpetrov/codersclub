<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Coders club Online radio</title>

  <!-- Bootstrap -->
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="css/app.css">

  <!-- Latest compiled and minified JavaScript -->

  <style>
    .ui-datepicker-month, .ui-datepicker-year {
      color: black;
    }
  </style>
  <link href="js/jquery-ui/jquery-ui.css" rel="stylesheet">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script
    src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="container">
  <div id="current-song">
    <h3><a href="#" class="show-current">Current song</a></h3>
    <ul class="hidden">
      <li><strong>Title</strong> <span class="title"></span></li>
      <li><strong>Album</strong> <span class="album"></span></li>
      <li><strong>Genre</strong> <span class="genre"></span></li>
      <li><strong>Duration</strong> <span class="duration"></span></li>
      <li><strong>Next in</strong> <span class="next"></span></li>
    </ul>
  </div>
  <div class="reports">
    <h3>Reports</h3>

    <form class="form-inline" method="post" accept-charset="utf-8" id="reportForm">
      <div class="form-group">
        <label class="sr-only" for="exampleInputEmail3">Date from</label>
        <input type="text" class="form-control" id="dateFrom"
               placeholder="Date from" name="dateFrom">
      </div>
      <div class="form-group">
        <label class="sr-only" for="exampleInputPassword3">Date to</label>
        <input type="text" class="form-control" id="dateTo"
               placeholder="Date to" name="dateTo">
      </div>
      <button type="submit" class="btn btn-default">Show report</button>
    </form>
    <ul id="reports" class="hidden">
      <li><strong>Most popular artist</strong> <span class="popularArtist"></span></li>
      <li><strong>Most popular song</strong> <span class="popularSong"></span></li>
      <li><strong>Longest song</strong> <span class="longest"></span></li>
      <li><strong>Shortest song</strong> <span class="shortest"></span></li>
      <li><strong>Top played genre</strong> <span class="popularGenre"></span></li>
      <li><strong>Top genre by songs</strong> <span class="topGenre"></span></li>
    </ul>
  </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script
  src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-ui/jquery-ui.js"></script>
<script src="js/codersClub.js"></script>
</body>
</html>

