<?php
//include 'global.php';
if(!isset($_SESSION['oauth_token']) OR !isset($_SESSION['oauth_token_secret']) OR !isset($_SESSION['linkedin_user_id'])){
	die('No session login');
}

// Validasi POST
if($_POST['category'] == '0') die('Please choose category');
else if($_POST['personal'] == false && $_POST['first'] == false) die('Please choose level');
else if($_POST['chart'] == '0') die('Please choose chart');


// Set Title
$titles = $db->get_single_data('category'," categoryId = '$_POST[category]'");
$title = $titles['categoryDesc'];

// Set POST level to Array
$levels = array();
if($_POST['personal']) $levels[] = array('label'=>'Personal', 'value'=>99);
if($_POST['first']) $levels[] =  array('label'=>'1st Level', 'value'=>1);

// Convert Array to String
$level = '(';
$label = '';
foreach($levels as $le){
	$level .= "connectionLevel = $le[value] OR "; 
	$label .= "'".$le['label']."'".',';
}
$level = substr($level,0,-3);
$level .= ')';
$label = substr($label,0,-1);

$data = $db->get_data('connection'," $level AND keywordId='$_POST[category]' ","*, DATE(dateCreated) AS creates");



// -list data
$lists = array(6,7,8,15,17,28,30);

// Jenis Chart
if($_POST['chart'] == 'line'){
	$chart = 'LineChart';
}
else if($_POST['chart'] == 'bar'){
	$chart = 'BarChart';
}
else if($_POST['chart'] == 'pie'){
	$chart = 'PieChart';
}
else if($_POST['chart'] == 'area'){
	$chart = 'AreaChart';
}
else if($_POST['chart'] == 'column'){
	$chart = 'ColumnChart';
}


// Jika kategori = -list kategori
if (in_array($_POST['category'], $lists)) {
	if(count($data) > 0 && $data != false){
		$total = 0;
		$date = '';
		$newdata = array();
		echo '<table id="flex">';
		echo 	'<thead>
					<tr>
						<th width="100">Date</th>
						<th width="100">User ID</th>
						<th width="150">Name</th>
						<th width="400">Value</th>
					</tr>
				</thead>
				<tbody>';
		
		foreach($data as $row){
			if(!empty($row['keywordValue'])) :
				echo '<tr>';
				echo	'<td width="100">'.$row['creates'].'</td>';
				echo	'<td width="100">'.$row['userId'].'</td>';
				echo	'<td width="150">'.$row['fullName'].'</td>';
				echo	'<td width="400">'.$row['keywordValue'].'</td>';
				echo '</tr>';			
			endif;
		}
		echo '<tbody></table>';
		?><script type="text/javascript">$('#flex').flexigrid();</script><?php
	}
	else{
		echo 'No data founded';
	}
}
else{
	if(count($data) > 0 && $data != false){
		$totalp = 0;
		$totalf = 0;
		$date = '';
		$newdata = array();
		$i = 0;
		$j = 0;
		foreach($data as $row){
			//echo $row['keywordValue']."<br />";
			
			if($_POST['type'] == 2){				
				//Jika yang dipilih adalah Average
				if($row['connectionLevel'] == '99'){
					if($datep != $row['creates']){
						$datep = $row['creates'];
						//echo $total/$i.'---';
						$i++;
					}
					
					$totalp += (int)$row['keywordValue'];
					$newdata[$datep]['personal'] = $totalp / $i;
				}
				
				if($row['connectionLevel'] == '1'){
					if($datef != $row['creates']){
						$datef = $row['creates'];
						//echo $total/$i.'---';
						//$totalf  = 0;
						$j++;
						$ja = 1;
					}
					else{
						$ja++;
					}
					
					$totalf += (int)$row['keywordValue'];
					$newdata[$datef]['first'] =  intval( $totalf / $ja ) ;
				}
			}
			else{
				//Jika yang dipilih adalah Total (Default Total)
				//Jika tanggal berubah maka rubah array;
				if($row['connectionLevel'] == '99'){
					if($datep != $row['creates']){
						$datep = $row['creates'];
						$totalp = 0;
					}
					
					$totalp += (int)$row['keywordValue'];
					$newdata[$datep]['personal'] = $totalp;
					
					//echo 'sendiri<br />';
				}
				
				if($row['connectionLevel'] == '1'){
					if($datef != $row['creates']){
						$datef = $row['creates'];
						$totalf = 0;
					}
					
					$totalf += (int)$row['keywordValue'];
					$newdata[$datef]['first'] = $totalf;
				}
			}
			
			
		}
	}
	else{
		echo 'No data founded';
	}
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          
			<?php
				$echo = "['Date',$label],";
				if(count($newdata)>0){
					foreach($newdata as $tgl=>$tot){
						$echo .= "['".$tgl."',";
						if($_POST['personal'])  $echo .= (int)$tot['personal'].",";
						if($_POST['first']) $echo .= (int)$tot['first'].",";						
						$echo = substr($echo,0,-1);
						$echo .= "],";
					}
					$echo = substr($echo,0,-1);
				}
				echo $echo;
			?>
        ]);

        var options = {
          title: <?="'".$title."'"?>,
          hAxis: {title: <?="'".$desc."'"?>,  titleTextStyle: {color: 'red'}}
        };
		
		
		<?php
		if(count($newdata)>0){ ?>
        var chart = new google.visualization.<?=$chart?>(document.getElementById('chart_div'));
        chart.draw(data, options);
		<?php } ?>
      }
    </script>


<div id="chart_div" style="width: 900px; height: 500px;"></div>
<?php
	//var_dump($newdata);
}
?>
