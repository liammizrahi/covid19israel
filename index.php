<?php
$api = file_get_contents('https://api.covid19api.com/country/israel');
$global = json_decode(file_get_contents('https://api.covid19api.com/summary'))->Global;
$data = json_decode($api);

$today = $data[count($data)-1];
$yesterday = $data[count($data)-2];
$active_today = intval($today->Confirmed) - intval($today->Recovered) - intval($today->Deaths);
$active_yesterday = intval($yesterday->Confirmed) - intval($yesterday->Recovered) - intval($yesterday->Deaths);
//echo var_dump($data);
function get_tag($value) {
    return $value['tag'];
}
$dates = Array();
$active = Array();
$total = Array();
$deathes = Array();
$recovered = Array();
foreach($data as $dt) {
  if(strtotime($dt->Date) > strtotime('2020-02-29')) {
    $dates[] = date('d/m', strtotime($dt->Date));
    $active[] = intval($dt->Confirmed) - intval($dt->Recovered) - intval($dt->Deaths);
    $total[] = intval($dt->Confirmed);
    $deathes[] = intval($dt->Deaths);
    $recovered[] = intval($dt->Recovered);
  }
}
 ?>
<!DOCTYPE html>
<html dir="rtl" lang="he">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>מספר חולי קורונה בישראל</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@700&display=swap" rel="stylesheet">
  <script src="https://use.fontawesome.com/b3c20d46fb.js"></script>
  <style>
  * {
    font-family: 'Assistant', sans-serif;
  }
  </style>
</head>
<body>
  <div class='container'>
    <center>
      <h1>תמונת מצב עדכנית: חולי קורונה בישראל <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Israel_flag_300.png" height=70px /></h1>
      <h4>פיתוח של ליאם מזרחי</h4>
      <h3 class="text-warning"><i class="fas fa-head-side-mask"></i> מספר מקרים כולל: <?= number_format($today->Confirmed); ?> <small>+<?= number_format($today->Confirmed-$yesterday->Confirmed); ?></small></h3>
      <h3 class="text-success"><i class="fas fa-skull-syringe"></i> מספר מחלימים: <?= number_format($today->Recovered); ?> <small>+<?= number_format($today->Recovered-$yesterday->Recovered); ?></small></h3>
      <h3 class="text-danger"><i class="fas fa-skull-crossbones"></i> מספר קורבנות: <?= number_format($today->Deaths); ?> <small>+<?= number_format($today->Deaths-$yesterday->Deaths); ?></small></h3>
      <h2 class="text-dark"><i class="fas fa-skull-virus"></i> מספר חולים עדכני <?= number_format(intval($today->Confirmed) - intval($today->Recovered) - intval($today->Deaths)); ?></h2>
      <h4>
        <? if($active_today > $active_yesterday): ?>
          עלייה של <?= number_format($active_today-$active_yesterday); ?>
        <? else: ?>
          ירידה של <?= number_format($active_yesterday-$active_today); ?>
        <? endif; ?>
        בכמות החולים
      </h4>
      <hr />
      <div class="row">
        <div class="col-md-8">
          <canvas id="myChart" width="400" height="250"></canvas>
        </div>
        <div class="col-md-4">
          <h1>בישראל</h1>
          <table class="table table-hover">
            <tr>
              <td>סה״כ מקרים מאומתים</td>
              <td><?= number_format($today->Confirmed); ?></td>
            </tr>
            <tr >
              <td>חולים חדשים</td>
              <td><?= number_format($today->Confirmed-$yesterday->Confirmed); ?></td>
            </tr>
            <tr>
              <td>סה״כ מתים</td>
              <td><?= number_format($today->Deaths); ?></td>
            </tr>
            <tr>
              <td>מתים חדשים</td>
              <td><?= number_format($today->Deaths-$yesterday->Deaths); ?></td>
            </tr>
            <tr>
              <td>סה״כ מחלימים</td>
              <td><?= number_format($today->Recovered); ?></td>
            </tr>
            <tr>
              <td>מחלימים חדשים</td>
              <td><?= number_format($today->Recovered-$yesterday->Recovered); ?></td>
            </tr>
            <tr>
              <td>סה״כ אנשים חולים בישראל כרגע</td>
              <td><?= number_format($active_today); ?></td>
            </tr>
          </table>
          <h1>בעולם</h1>
          <table class="table table-hover">
            <tr>
              <td>סה״כ מקרים מאומתים</td>
              <td><?= number_format($global->TotalConfirmed); ?></td>
            </tr>
            <tr >
              <td>חולים חדשים</td>
              <td><?= number_format($global->NewConfirmed); ?></td>
            </tr>
            <tr>
              <td>סה״כ מתים</td>
              <td><?= number_format($global->TotalDeaths); ?></td>
            </tr>
            <tr>
              <td>מתים חדשים</td>
              <td><?= number_format($global->NewDeaths); ?></td>
            </tr>
            <tr>
              <td>סה״כ מחלימים</td>
              <td><?= number_format($global->TotalRecovered); ?></td>
            </tr>
            <tr>
              <td>מחלימים חדשים</td>
              <td><?= number_format($global->NewRecovered); ?></td>
            </tr>
            <tr>
              <td>סה״כ אנשים שחולים כרגע בעולם</td>
              <td><?= number_format(intval($global->TotalConfirmed) - intval($global->TotalDeaths) - intval($global->TotalRecovered)); ?></td>
            </tr>
          </table>
        </div>
      </div>
    </center>
  </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($dates); ?>,
        datasets: [{
            label: 'חולים אקטיביים',
            data: <?= json_encode($active); ?>,
            borderColor: "#2ab7ca",
            fill: false
        },
        {
            label: 'סך הכל מקרים',
            data: <?= json_encode($total); ?>,
            borderColor: "#fed766",
            fill: false
        },
        {
            label: 'חולים שהחלימו',
            data: <?= json_encode($recovered); ?>,
            borderColor: "#88d8b0",
            fill: false
        },
        {
            label: 'מתים',
            data: <?= json_encode($deathes); ?>,
            borderColor: "#fe4a49",
            fill: false
        }]
    },
    options: {
      title: {
        display: true,
        text: 'מספר חולים אקטיביים לפי תאריך'
      }
    }
});
</script>
</html>
