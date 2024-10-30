<?php
/*
Plugin Name: Motorradbekleidung
Plugin URI: http://wordpress.org/extend/plugins/motorradbekleidung/
Description: Adds a customizeable widget which displays the latest Motorradbekleidung news by http://www.motorradbekleidung-guenstig.com/
Version: 1.0
Author: Hans Eisele
Author URI: http://www.motorradbekleidung-guenstig.com/
License: GPL3
*/

function motorradbekleidungnews()
{
  $options = get_option("widget_motorradbekleidungnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Motorradbekleidung',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://news.google.de/news?pz=1&cf=all&ned=de&hl=de&q=fahrrad&cf=all&output=rss'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_motorradbekleidungnews($args)
{
  extract($args);
  
  $options = get_option("widget_motorradbekleidungnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Motorradbekleidung',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  motorradbekleidungnews();
  echo $after_widget;
}

function motorradbekleidungnews_control()
{
  $options = get_option("widget_motorradbekleidungnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Motorradbekleidung',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['motorradbekleidungnews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['motorradbekleidungnews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['motorradbekleidungnews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['motorradbekleidungnews-CharCount']);
    update_option("widget_motorradbekleidungnews", $options);
  }
?> 
  <p>
    <label for="motorradbekleidungnews-WidgetTitle">Widget Title: </label>
    <input type="text" id="motorradbekleidungnews-WidgetTitle" name="motorradbekleidungnews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="motorradbekleidungnews-NewsCount">Max. News: </label>
    <input type="text" id="motorradbekleidungnews-NewsCount" name="motorradbekleidungnews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="motorradbekleidungnews-CharCount">Max. Characters: </label>
    <input type="text" id="motorradbekleidungnews-CharCount" name="motorradbekleidungnews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="motorradbekleidungnews-Submit"  name="motorradbekleidungnews-Submit" value="1" />
  </p>
  
<?php
}

function motorradbekleidungnews_init()
{
  register_sidebar_widget(__('Motorradbekleidung'), 'widget_motorradbekleidungnews');    
  register_widget_control('Motorradbekleidung', 'motorradbekleidungnews_control', 300, 200);
}
add_action("plugins_loaded", "motorradbekleidungnews_init");
?>