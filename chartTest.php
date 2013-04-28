<?php
require('gchart/utility.php');
require ('gchart/gChart.php');
require ('gchart/gPieChart.php');


$piChart = new gchart\gPieChart();
$piChart->addDataSet(array(112,315,66,40, 90));
$piChart->setLegend(array("A", "B", "C","D", "F"));
// $piChart->setLabels(array("first", "second", "third","fourth"));
$piChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));
$piChart->renderImage(0);
?>
<img src="<?php echo $piChart->getUrl();  ?>" />
