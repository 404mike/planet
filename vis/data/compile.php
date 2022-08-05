<?php

class GenerateGraphData {

  private $nodes = [];
  private $edges = [];
  private $author = [];
  private $subjects = [];
  private $locations = [];

  public function __construct()
  {
    $this->getCsvData();

    $this->generateData();
  }

  private function getCsvData()
  {
    $data = [];
    $files = glob('../../../data/*.{csv}', GLOB_BRACE);
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
    foreach($data as $k => $v)
    {
      $person = $v['Author'];
      $pId = $this->addAuthor($person);

      if(empty($pId)) {

        continue;

      }

      $locations = $this->formatData($v['Subject location'], 'loc');
      $subjects = $this->formatData($v['Subject keywords'], 'sub');
      
      if(!empty($locations)) {
        foreach($locations as $lk => $lv) {

          if(empty($lv)) continue;

          $this->edges[] = [
            'from' => $pId,
            'to' => $lv
          ];
        }
      }

      if(!empty($subjects)) {
        foreach($subjects as $sk => $sv) {

          if(empty($sv)) continue;

          $this->edges[] = [
            'from' => $pId,
            'to' => $sv
          ];
        }
      }
    }
  }

  private function addAuthor($author)
  {
    if(empty($author)) return;

    $key = array_search($author, array_column($this->nodes, 'label'));

    if(!empty($key)) return $key;

    $this->nodes[] = ['label' => $author, 'group' => 1];
    $key = array_search($author, array_column($this->nodes, 'label'));
    return $key;
  }

  private function addSubject($sub)
  {
    if(empty($sub)) return;

    $key = array_search($sub, array_column($this->nodes, 'label'));

    if(!empty($key)) return $key;

    $this->nodes[] = ['label' => $sub, 'group' => 2];
    $key = array_search($sub, array_column($this->nodes, 'label'));
    return $key;
  }

  private function addLocation($loc)
  {
    if(empty($loc)) return;

    $key = array_search($loc, array_column($this->nodes, 'label'));

    if(!empty($key)) return $key;

    $this->nodes[] = ['label' => $loc, 'group' => 3];
    $key = array_search($loc, array_column($this->nodes, 'label'));
    return $key;
  }



  private function formatData($data, $type)
  {
    if(empty($data)) return [];

    $arr = explode(';',$data);

    $d = [];

    foreach($arr as $k => $v) {
      $v = trim($v);

      if($type = 'sub') {
        $d[] = $this->addSubject($v);
      }
      if($type = 'loc') {
        $d[] =$this->addLocation($v);
      }
    }

    return $d;
  }

  private function generateData()
  {
    $n = $this->generateNodes($this->nodes);
    $e = $this->generateEdges($this->edges);
    // echo $n;
    // print_r($this->edges);

    $str = "$n \n $e";
    file_put_contents('../assets/data.js',$str);
  } 

  private function generateNodes($nodes)
  {
    $arr = [];

    foreach($nodes as $k => $v) {
      $label = $v['label'];
      $group = $v['group'];
      $arr[] = '{ id: '.$k.', label: "'.$label.'", group: '.$group.' }';
    }

    $str = 'var nodes = [' ."\n";
    $str .= implode(",\n",$arr);
    $str .= ']';

    return $str;
  }

  private function generateEdges($edges)
  {
    $arr = [];

    foreach($edges as $k => $v) {
      $from = $v['from'];
      $to = $v['to'];
      $arr[] = '{ from: '.$from.', to: '.$to.' }';
    }

    $str = 'var edges = [' . "\n";
    $str .= implode(",\n",$arr);
    $str .= ']';

    return $str;

  }


}

(new GenerateGraphData());