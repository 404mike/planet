<?php

class CompileCSV {

  private $data = [];
  private $inc = 0;

  public function __construct()
  {
    $this->getFiles();

    $this->generateJson();
  }

  private function getFiles()
  {
    $files = glob('../../data/*.{csv}', GLOB_BRACE);
    foreach($files as $file) {
      $this->loadCsvFile($file);
    }
  }

  private function loadCsvFile($file)
  {
    $data = $this->csv_to_array($file);
    $this->parseData($data);
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

  private function parseData($data)
  {
    foreach($data as $k => $v) {
      
      if(empty($v['Author'])) continue;


      $this->data[] = [
        'id' => $this->inc,
        'author' => $v['Author'],
        'title' => $v['Article title'],
        'date' => $v['Date'],
        'issue' => $v['Issue'],
        'location' => $v['Subject location'],
        'keyword' => $v['Subject keywords']
      ];


      $this->inc++;

    }
  }

  private function generateJson()
  {
    file_put_contents('planet.json',json_encode($this->data,JSON_PRETTY_PRINT));
  }

  
}

(new CompileCSV());