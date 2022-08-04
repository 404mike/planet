<?php

class GenerateGeoData {

  private $existingData = [];
  private $newGeoData = [];
  private $locationIssues = [];

  public function __construct()
  {
    $this->getExistingData();

    $this->getCsvData();

    $this->writeNewExistingLocationData();
  }

  private function getExistingData()
  {
    $json = file_get_contents('location_data.json');
    $data = json_decode($json,true);

    foreach($data as $k => $v) {
      $this->existingData[$k] = $v;
    }
  }

  private function getCsvData()
  {
    $data = [];
    $files = glob('../../data/*.{csv}', GLOB_BRACE);
    foreach($files as $file) {
      $csv = $this->csv_to_array($file);

      foreach($csv as $k => $v) {
        $data[] = $v;
      }

    }

    $this->parseCsvData($data);
  }

  private function csv_to_array($filename='', $delimiter=',')
  {
    if(!file_exists($filename) || !is_readable($filename))
      return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
      while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
      {
        if(!$header)
          $header = $row;
        else
          $data[] = array_combine($header, $row);
      }
      fclose($handle);
    }
    return $data;
  }

  private function parseCsvData($data)
  {
    $locations = [];

    foreach($data as $k => $v)
    {
      $date = $v['Date'];
      $loc = explode(';',$v['Subject location']);

      if(empty($data)) continue;
      if(empty($loc)) continue;

      foreach($loc as $lk => $lv) {
        if(!empty($lv))
          $locations[$date][] = trim($lv);
      }      
    }

    $this->parseLocations($locations);
  }

  private function parseLocations($locations)
  {
    foreach($locations as $date => $loc) {
      
      foreach($loc as $k => $v){
        $geo = $this->checkExistingLocation($v);
      }

    }
  }

  private function checkExistingLocation($loc)
  {
    if(isset($this->existingData[$loc])) {
      return $this->existingData[$loc];
    }else{
      return $this->getNewLocation($loc);
    }
  }

  private function getNewLocation($loc)
  {
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$loc%20wales&sensor=false&key=AIzaSyCPrQCZV9enCriliqZQBhKBi9KKayo5IqY";

    @$json = file_get_contents($url);

    if($json === FALSE) {
      $this->locationIssues[] = $loc;
      return '';
    }
    
    $data = json_decode($json,true);

    if($data['status'] == 'ZERO_RESULTS' || $data['status'] == 'OK') {
      return $this->formatGoogleMapsResults($data, $loc);
    }

    elseif($data['status'] != 'OK') {
      echo $json;
      die('quota error');
    }

    sleep(2);

  }

  private function formatGoogleMapsResults($data, $loc)
  {

    if(!isset($data['results'][0]['geometry']['bounds'])) {
      $this->locationIssues[] = $loc;
      return "";
    }

    $lat = $data['results'][0]['geometry']['bounds']['northeast']['lat'];
    $lng = $data['results'][0]['geometry']['bounds']['northeast']['lng'];

    $this->existingData[$loc] = "$lat, $lng";

    return "$lat, $lng";
  }

  private function writeNewExistingLocationData()
  {
    file_put_contents('location_data.json', json_encode($this->existingData, JSON_PRETTY_PRINT));

    $issues = array_unique($this->locationIssues);
    file_put_contents('location_issues.json', json_encode($issues, JSON_PRETTY_PRINT));
  }

}

(new GenerateGeoData());