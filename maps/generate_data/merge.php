<?php

class MergeGeo {

  private $existingGeo = [];


  public function __construct()
  {
    $this->loadExistingGeo();

    $this->loadNewGeoData();
  }

  private function loadExistingGeo()
  {
    $json = file_get_contents('location_data.json');
    $data = json_decode($json,true);
    $this->existingGeo = $data;
  }

  private function loadNewGeoData()
  {
    $arr = $this->csv_to_array('resolved_locations.csv');
    $this->mergeDocs($arr);
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

  private function mergeDocs($newLoc)
  {
    foreach($newLoc as $k => $v) {

      if(empty($v['geo'])) continue;

      $place = $v['Place'];
      $latlng = $v['geo'];
      $this->existingGeo[$place] = $latlng;
    }

    print_r($this->existingGeo);

    file_put_contents('location_data.json', json_encode($this->existingGeo,JSON_PRETTY_PRINT));
  }


}

(new MergeGeo());