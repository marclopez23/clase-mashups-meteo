<?php
header('Content-Type: text/html; charset=utf-8');
/*Localización*/
$localizacion = urlencode("Mataro");
$url_geo= "https://api.opencagedata.com/geocode/v1/json?q=".$localizacion."&key=ca75ee13fcf2422fb10ac41b895c56e8&limit=1";
$res2=file_get_contents($url_geo);
$data2=json_decode($res2);
$lat= $data2->results[0]->geometry->lat;
$lng= $data2->results[0]->geometry->lng;
/*meteo*/
$url="https://api.darksky.net/forecast/6da55ae4d3b635780617e4cf3bd9d2ca/".$lat.",".$lng."?units=si&lang=es";
$res=file_get_contents($url);
$data=json_decode($res);
print_r($url);

date_default_timezone_set('UTC+2');
setlocale(LC_TIME,"es_ES");


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/reset.css">
  <link rel="stylesheet" href="./css/estilos.css">
  <title></title>
</head>
<body>
  <div class="wrapper">
    <div class="actual">
      <?php
      echo strftime("%A %d de %B", $data->currently->time);
      echo '<p>'.round($data->currently->temperature)?> Cº. <?php echo($data->currently->summary).'</p>';
      echo '<canvas class="'.$data->currently->icon.'" width="40" height="80"></canvas>';
      ?>
    </div>
    <div class="semana">
      <?php
      $dia = $data->daily->data;
      for ($i = 2; $i <= 6; $i++) {
        $fecha=$dia[$i]->time;
        $fecha=utf8_encode($fecha);
        $fecha=utf8_decode($fecha);
        $icon=$dia[$i]->icon;
        echo '<div class="dia">';
        echo '<p>'.strftime("%A %d de %B",$fecha ).'</p>';
        echo '<p>'.round($dia[$i]->temperatureMin).' Cº'.'</p>';
        echo '<p>'.(($dia[$i]->humidity)*100).'%'.'</p>';
        echo '<p>'.(($dia[$i]->cloudCover)*100).'%'.'</p>';
        echo '<p>'.round($dia[$i]->temperatureMax).' Cº'.'</p>';
        echo '<p>'.($dia[$i]->summary).'</p>';
        echo '<canvas class="'.$icon.'" width="40" height="80"></canvas>';
        echo '</div>';
      }?>
    </div>


  </div>
  <script src="./js/skycons.js">

  </script>
  <script>
    var skycons = new Skycons({"color": "#ffffff"}),
        list  = [
          "clear-day", "clear-night", "partly-cloudy-day",
          "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
          "fog"
        ],
        i;

      for(i = list.length; i--; ) {
          var weatherType = list[i],
              elements = document.getElementsByClassName( weatherType );
          for (e = elements.length; e--;){
              skycons.set( elements[e], weatherType );
          }
      }

    skycons.play();
  </script>
</body>
</html>
