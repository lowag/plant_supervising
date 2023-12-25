<?php
if (isset($_GET['page'])){
        switch ($_GET['page']){
                case "location1":
                        $topic="company/location1/machines/+";
                        //list all the machines you can find at the location1
                        $machines=array('machine1','machine2',"machine3");

                        break;
                case "location2":
                        $topic="company/location2/machines/+";
                        //list all the machines you can find at the location2
                        $machines=array('machine1','machine2',"machine3");
                        break;
                case "location3":
                        $topic="company/location3/machines/+";
                        //list all the machines you can find at the location3
                        $machines=array('machine1','machine2',"machine3");
                        break;
                case "location4":
                        $topic="company/location4/machines/+";
                        //list all the machines you can find at the location4
                        $machines=array('machine1','machine2',"machine3");

                        break;
                case "all":
                        $topic="company/+/machines/+";
                       	//list all the machines at the company
                       	$machines=array('machine1','machine2','machine3');
                        break;
                default:$topic="company/location1/machines/+";
                        $machines=array('machine1','machine2',"machine3");


        }




}else
{$topic="company/location1/machines/+";
 $machines=array('machine1','machine2',"machine3");

}

?>
	<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Plantinfo</title>
    <meta name="description" content="Libremaint">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="bootstrap.min.css">
<link rel="stylesheet" href="open_sans.css">
<script src="node_modules/canvas-gauges/gauge.min.js"></script>
<script src="vendor/mqtt/mqttws31.js"></script>
<script>
var host = "MQTT_broker_IP_ADDRESS";

// Create a client instance
client = new Paho.MQTT.Client(host,9001, "browser_<?php echo rand();?>");

// set callback handlers
client.onConnectionLost = onConnectionLost;
client.onMessageArrived = onMessage;

// connect the client
client.connect({onSuccess:onConnect,keepAliveInterval: 30});

//client.subscribe("Locationhu/machines/alfa3");

// called when the client connects
function onConnect() {
  // Once a connection has been made, make a subscription and send a message.
  console.log("onConnect");
//  client.subscribe("server");
  client.subscribe("<?php echo $topic; ?>");
  message = new Paho.MQTT.Message('{"topic":"<?php echo $topic; ?>"}');
  message.destinationName = "server";
  client.send(message);
}

// called when the client loses its connection
function onConnectionLost(responseObject) {
  if (responseObject.errorCode !== 0) {
    console.log("onConnectionLost:"+responseObject.errorMessage);
alert("Connection lost!")
location.reload();  
}

}



const xn = [<?php

echo "'" . implode ( "', '", $machines ) . "'";

?>];

for (var i = 0; i < xn.length; i++) {
window[xn[i]+'_actual_speed']=0;
window[xn[i]+'_actual_length']=0;
window[xn[i]+'_shift_length']=0;
window[xn[i]+'_uptime']=0;
window[xn[i]+'_downtime']=0;
}




  function onMessage(message)
  {console.log("1"+message.payloadString)
    	if (/^[\],:{}\s]*$/.test(message.payloadString.replace(/\\["\\\/bfnrtu]/g, '@').
replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
const mes = JSON.parse(message.payloadString);
<?php

if (!isset($_GET["page"]) || (isset($_GET["page"]) && $_GET["page"]!="all")){ ?>
	
 	var canvas = document.getElementById(mes.machine);
        var ctx = canvas.getContext("2d");
	if (mes.hasOwnProperty('speed')){
  	var gauge = document.gauges.get(mes.machine);
	window[mes.machine+'_actual_speed']=mes.speed;
        
	}
//        alert(evt.data);
        console.log(message.payloadString)
        if (mes.hasOwnProperty('speed')){
	gauge.update({ value: mes.speed});
	if (mes.speed<15)
	document.getElementById(mes.machine).style.backgroundColor = "#fce1e4";     
	
	else if (mes.speed>=15)
	 document.getElementById(mes.machine).style.backgroundColor = "#defbe0";
	
	}
	
//        var ctx = canvas.getContext("2d");


	if (mes.hasOwnProperty('uptime'))
        {window[mes.machine+'_uptime']=mes.uptime;

	}

	if (mes.hasOwnProperty('downtime'))
	{window[mes.machine+'_downtime']=mes.downtime;

	}

	 if (mes.hasOwnProperty('actual_length')){
        ctx.clearRect(-40, 75, 60, 17);
        window[mes.machine+'_actual_length']=mes.actual_length;

	ctx.fillStyle = 'black';
        ctx.font = "15px Arial";
        ctx.textAlign = "center";

        ctx.fillStyle = "blue";
        ctx.fillText(window[mes.machine+'_actual_length']+" m",0,90);
        }

	if (mes.hasOwnProperty('shift_length')){
	ctx.clearRect(-65, 100, 130, 30);
        
        window[mes.machine+'_shift_length']=mes.shift_length;
	ctx.font = "30px Arial";
	ctx.textAlign = "center";
        ctx.fillText(window[mes.machine+'_shift_length']+" m",0,130);
	}

	ctx.clearRect(-80, 140, 160, 20);	
	ctx.font = "15px Arial";
        ctx.textAlign = "center";
	if (window[mes.machine+'_actual_speed']>15){
	ctx.fillStyle = "green";
        ctx.fillText(window[mes.machine+'_uptime'],0,160);
        }
	
	if (window[mes.machine+'_actual_speed']<=15){
	ctx.clearRect(-80, 140, 160, 20);
        ctx.textAlign = "center";
        ctx.fillStyle = "red";
//ctx.fillStyle = "red";
//ctx.fillRect(-80, 140, 160, 20);        
ctx.fillText(window[mes.machine+'_downtime'],0,160);
       }


<?php        }
else{?>

if (mes.hasOwnProperty('speed')){
        document.getElementById(mes.machine+"_act_speed").innerHTML = mes.speed+' m/min';
        if (mes.speed<15)
        document.getElementById(mes.machine+"_row").style.backgroundColor="#fce1e4";
        else
        document.getElementById(mes.machine+"_row").style.backgroundColor="#defbe0";

}
if (mes.hasOwnProperty('downtime')){
        document.getElementById(mes.machine+"_downtime").innerHTML = mes.downtime;
}
if (mes.hasOwnProperty('uptime')){
        document.getElementById(mes.machine+"_uptime").innerHTML = mes.uptime;
}
if (mes.hasOwnProperty('shift_max_speed')){
        document.getElementById(mes.machine+"_shift_max_speed").innerHTML = mes.shift_max_speed+' m/min';
}
if (mes.hasOwnProperty('max_speed')){
        document.getElementById(mes.machine+"_max_speed").innerHTML = mes._max_speed+' m/min';
}
if (mes.hasOwnProperty('shift_length')){
        document.getElementById(mes.machine+"_shift_length").innerHTML = mes.shift_length+' m';
}if (mes.hasOwnProperty('max_shift_length')){
        document.getElementById(mes.machine+"_max_shift_length").innerHTML = mes.max_shift_length+' m';
}
if (mes.hasOwnProperty('sum_shift_shutdown')){
        document.getElementById(mes.machine+"_sum_shift_shutdown").innerHTML = mes.sum_shift_shutdown+' min';
}
if (mes.hasOwnProperty('utilization')){
        document.getElementById(mes.machine+"_utilization").innerHTML = mes.utilization+' %';
}
<?php } ?>
}
  }



</script>
</head>
<body style="font-family: 'Open Sans';font-size: 22px;color: black">
<nav>
<div class="nav nav-tabs" id="nav-tab" role="tablist">
<a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="index.php?page=location1" role="tab" aria-controls="nav-home" aria-selected="<?php
if (isset($_GET['page']) && $_GET['page']=="location1")
echo "true\" class=\"nav-item nav-link active show\"";
else
echo "false\" class=\"nav-item nav-link\"";
?>
">Location Hu</a>
<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="index.php?page=location2" role="tab" aria-controls="nav-profile" aria-selected="<?php
if (isset($_GET['page']) && $_GET['page']=="location2")
echo "true\" class=\"nav-item nav-link active show\"";
else
echo "false\" class=\"nav-item nav-link\"";
?>
">Location It</a>
<a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="index.php?page=location3" role="tab" aria-controls="nav-contact" aria-selected="<?php
if (isset($_GET['page']) && $_GET['page']=="location3")
echo "true\" class=\"nav-item nav-link active show\"";
else
echo "false\" class=\"nav-item nav-link\"";
?>
">Location Ro</a>
<a id="nav-contact-tab" data-toggle="tab" href="index.php?page=location4" role="tab" aria-controls="nav-contact" aria-selected="<?php
if (isset($_GET['page']) && $_GET['page']=="location4")
echo "true\" class=\"nav-item nav-link active show\"";
else
echo "false\" class=\"nav-item nav-link\"";
?>ˇ
>Location CZ</a>

<a class="nav-item nav-link active show" id="nav-contact-tab" data-toggle="tab" href="index.php?page=all" role="tab" aria-controls="nav-contact" aria-selected="<?php
if (isset($_GET['page']) && $_GET['page']=="all")
echo "true\" class=\"nav-item nav-link active show\"";
else
echo "false\" class=\"nav-item nav-link\"";
?>
">All site</a>


</div>
</nav>
<?php
if ((isset($_GET['page']) && $_GET['page']=="location1") || !isset($_GET['page']))
{
echo "<div class=\"row\">";
        include("machine_gauges/machine1.php");
        include("machine_gauges/machine2.php");
        include("machine_gauges/machine3.php");

echo "</div>\n";


}

else if (isset($_GET['page']) && $_GET['page']=="location3")
{
echo "<div class=\"row\">";
        include("machine_gauges/machine1.php");
        include("machine_gauges/machine2.php");
echo "</div>\n";
}
else if (isset($_GET['page']) && $_GET['page']=="location4")
{
echo "<div class=\"row\">";
        include("machine_gauges/machine1.php");
        include("machine_gauges/machine2.php");
echo "</div>\n";
}
else if (isset($_GET['page']) && $_GET['page']=="location2")
{
echo "<div class=\"row\">";
        include("machine_gauges/machine1.php");
        include("machine_gauges/machine2.php");
echo "</div>\n";


}
else if (isset($_GET['page']) && $_GET['page']=="all")
{
echo "<table class=\"table table-striped\">\n";
echo "<thead><tr>\n";
echo "<th>Machine</th><th>Last stop</th><th>Last start</th><th>Actual speed</th><th>Shift maxspeed</th><th>max speed</th><th>Shift length</th><th>Max shift length</th><th>Sum shift shutdown</th><th>Utilization</th>\n";
echo "</tr></thead>\n";
foreach ($machines as $machine)
{
        echo "<tr id='".$machine."_row'><td>".ucfirst($machine)."</td>";
        echo "<td id='".$machine."_downtime'></td>";
        echo "<td id='".$machine."_uptime'></td>";
        echo "<td id='".$machine."_act_speed'></td>";
        echo "<td id='".$machine."_shift_max_speed'></td>";
        echo "<td id='".$machine."_max_speed'></td>";
        echo "<td id='".$machine."_shift_length'></td>";
        echo "<td id='".$machine."_max_shift_length'></td>";
        echo "<td id='".$machine."_sum_shift_shutdown'></td>";
        echo "<td id='".$machine."_utilization'></td></tr>\n";
}


echo "</table>";
}
?>
</body>
</html>
