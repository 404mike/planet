<?php

class CompileCSV {

  private $data = [];
  private $keywords = [];

  public function __construct()
  {
    $this->getFiles();

    $this->generateCSV();

    // print_r($this->data);
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
      
      if(empty($v['Date'])) continue;

      $date = date('Y-m-01',strtotime($v['Date']));

      $this->formatKeywords($v['Subject keywords'], $date);

    }
  }

  private function formatKeywords($keywords, $date)
  {
    $bow = explode(';',$keywords);

    foreach($bow as $k => $v) {

      $v = trim($v);

      if(empty($v)) continue;

      // $v = str_replace(['(',')'],'',$v);

      // echo "trying |$v|\n";

      if(isset($this->keywords[$v])) {
        $count = $this->keywords[$v] += 1;
      }else{
        $count = $this->keywords[$v] = 1;
      }

      // echo $v . ' = ' . $this->keywords[$v] . "\n";
      $this->data[$date] = $this->keywords;
    }    
  }

  private function generateCSV()
  {
    $list = array (
      array('date','name','category','value')
    );

    foreach($this->data as $k => $v) {

      foreach($v as $kk => $vv) {
        $list[] = [
          $k, $kk, 'some', $vv
        ];
      }

    }


    $fp = fopen('planet.csv', 'w');

    foreach ($list as $fields) {
      fputcsv($fp, $fields);
    }

    fclose($fp);
  }

  private function generateJson()
  {
    file_put_contents('planet.json',json_encode($this->data,JSON_PRETTY_PRINT));
  }

  
}

(new CompileCSV());