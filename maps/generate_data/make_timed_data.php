<?php

class MakeTimedData {

  private $existingData = [];
  private $newGeoData = [];
  private $locationIssues = [];

  public function __construct()
  {
    $this->getExistingData();

    $this->getCsvData();
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
        if(!empty($lv)) {

          $dateFormatted = $this->formatDate($date);

          if(!empty($dateFormatted)) {
            $locations[$dateFormatted][] = [
              'place' => trim($lv),
              'geo' => $this->getLocation(trim($lv))
            ];
          }
        }
          
      }      
    }

    ksort($locations);
    
    $this->outputResults($locations);
  }

  private function getLocation($loc)
  {
    // TODO fix this issue
    if(!isset($this->existingData[$loc])) {
      return '';
    }
    return $this->existingData[$loc];
  }

  private function formatDate($date)
  {
    $arr = explode('-',$date);

    if(!isset($arr[1])) {
      return '';
    }

    $year = $arr[1];
    $month = $arr[0];

    $newDate = $year . '-' . $month . '-01';
    return date('Ymd',strtotime($newDate));

  }

  private function outputResults($locations)
  {
    file_put_contents('planet_locations.json',json_encode($locations));
  }

}

(new MakeTimedData());