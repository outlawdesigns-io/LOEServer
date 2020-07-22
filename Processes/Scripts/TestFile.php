<?php namespace LOE;

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/../../Libs/ComicVine/ComicVine.php';

$model = Factory::getModel(Comic\Comic::TABLE);
$scanner = Factory::createHoldingBayScanner($model);

foreach($scanner->targetModels as $comic){
  if(!empty($comic->issue_title) && !empty($comic->issue_cover_date)){
      $results = \ComicVine::search($comic->issue_title);
      $volume = \ComicVine::followURI($results->results->volume->api_detail_url);
      $comic->series_title = (string)$volume->results->name;
      $comic->series_start_year = (int)$volume->results->start_year;
      $comic->series_description = strip_tags((string)$volume->results->description);
      $comic->publisher = (string)$volume->results->publisher->name;
      print_r($comic);
      // $issues = $volume->results->issues->issue;
      // $issueName = $issues->name;
      exit;
  }
}

// $issueStrs = array("Batman - The Red Death 001","Teen Titans 012","Dark Nights - Metal 002");
//
// foreach($issueStrs as $str){
//   $results = \ComicVine::search($str);
//   foreach($results->results->volume as $volume){
//     echo $volume->name . "\n";
//   }
// }

// $comic = Factory::createModel(Comic\Comic::TABLE);
// $comic->issue_number = (float)$issueDetails->results->issue_number;
// $comic->issue_title = (string)$issueDetails->results->name;
// $comic->issue_cover_date = (string)$issueDetails->results->cover_date;
// $comic->series_title = (string)$issueDetails->results->volume->name;
// $comic->series_start_year = (string)$startYear;
// $comic->series_end_year = "";
// $comic->story_arc = (string)$issueDetails->results->story_arc_credits->story_arc->name;
// $comic->issue_description = strip_tags((string)$issueDetails->results->description);
// $comic->series_description = strip_tags((string)$seriesDescription);
// $comic->issue_type = "";
// $comic->publisher = $publisher;
// $comic->file_path = $series->files[array_search($comic->issue_number,$series->issues)];
