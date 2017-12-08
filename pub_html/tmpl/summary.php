                    </div>
                    <div class="callout-hr"></div>                        
                    <div class="container">
                          


                   		<div class="block-icons">
                            <div class="one-third column">
                                <div class="icon">
                                    <div class="symbol iphone"></div>
                                    <h5>Voice Recording Statistics</h5>
                                    <p>
										<ul><li>Total Audio Percentage: <?php echo $variables['totalAduioPerc']; ?>%</li></ul>
                                    </p>
                                </div>
                            </div>
                            <div class="one-third column">
                                <div class="icon">
                                    <div class="symbol iphone"></div>
                                    <h5>Screen Recording Statistics</h5>
                                    <p>
										<ul><li>Total Screen Percentage: <?php echo $variables['totalScreenPerc']; ?>%</li></ul>
                                    </p>
                                </div>
                            </div>
                           <div class="one-third column">
                                <div class="icon">
                                    <div class="symbol client"></div>
                                    <h5>Metadatas Statistics</h5>
                                    <p>
										<ul><li>Total Metadata Percentage: <?php echo $variables['metadatas']; ?>%</li></ul>
                                    </p>
                                </div>
                            </div>
                        </div>

						
						<div class="sixteen columns">         
                            
							<!-- carousel starts -->
                            <div class="slidewrap">
  <!--                          	<div class="title-wrapper"> -->
                                    <div class="section-title">
                                        <h4 class="title">Summary of <strong>the day</strong></h4><br />
                                    </div>
									
<?php									
//var_dump($variables);
?>                                   
                                        
                                        
                                        
                                        
                                    
<!--                                    <ul class="slidecontrols">
                                        <li><a href="#sliderName" class="next">Next</a></li>
                                        <li><a href="#sliderName" class="prev">Prev</a></li>
                                    </ul>
                                    <span class="divider"></span>
-->
                                    <div class="clear"></div>
                                </div>

                                        <div>
                                        	<div class="content">
                                                <div class="border">

												
												
												
												                <table class="globalStyats">
                    <tr>
                        <th>Site Name</th>
                        <th>Total</th>
                        <th>Percentage of screen recording</th>
                        <th>Percentage of Audio recording</th>
                        <th>Total EREC in TwoStage</th>
                        <th>Total Old EREC in TwoStage</th>
						<th>Total Old ESPX in Recording</th>
						<th>Total Old EREC in Recording</th>
                        <th>Missing Audio (espx at 0Ko)</th>
						<th>Metadatas</th>
                    </tr>                        
            <?php                            
           	
            foreach($variables['sites'] as $site){    

                if ($site != 'totalScreen' && $site != 'totalAudio' && $site != 'totalAduioPerc' && $site != 'totalScreenPerc') {
                                       
                    $color = '';
                    
                    if (($site->getAudioThreshold() < $site->getAudioPercentage()  || $site->getAudioThreshold() == 0) && ($site->getScreenThreshold() < $site->getScreenPercentage() || $site->getScreenThreshold() == 0) ) {
                    	$color = '#9ACD32';
                    }else {
                    	$color = '#FF0000';
                    }
                    
                    if ($site->getTotalEspx() <= 0){
                    	echo '<tr><td>',$site->getName(),'</td><td colspan=7 style=\'text-align:center;vertical-align:middle\'>No Data</td></tr>';
                    }else if($site->isHolliday()){
                    	echo '<tr><td>',$site->getName(),'</td><td colspan=7 style=\'text-align:center;vertical-align:middle\'>Holiday</td></tr>';
                    }else{
                    	echo '<tr><td>',$site->getName(),'</td><td style=\'color:',$color,';text-align:center;vertical-align:middle\'>',$site->getTotalEspx(),'</td><td style=\'color:',$color,';text-align:center;vertical-align:middle\'>',$site->getScreenPercentage(),' %</td><td style=\'color:',$color,';text-align:center;vertical-align:middle\'>',$site->getAudioPercentage(),'%</td><td style=\'color:',$color,';text-align:center;vertical-align:middle\'>',$site->getErecTwoStage(),'</td><td style=\'color:',$color,';text-align:center;vertical-align:middle\'>',$site->getOldErecInTwoStageLocation(),'</td><td style=\'color:',$color,';text-align:center;vertical-align:middle\'>',$site->getOldEspxRecordingFolder(),'</td><td style=\'color:',$color,';text-align:center;vertical-align:middle\'>',$site->getOldErecRecordingFolder() ,'</td><td style=\'color:',$color,';text-align:center;vertical-align:middle\'>',$site->getTotalMissingVoice(),' </td><td style=\'color:',$color,';text-align:center;vertical-align:middle\'>', $site->getMetadataPercentage(), '%</td>';		
                    	echo '</tr>';
                    }
                 }
              }            
              ?>   
            </table>
			<br /><br />
			                                    <div class="clear"></div>
                                </div> </div> </div>
								<div class="slidewrap">
                                    <div class="section-title">
                                        <h4 class="title">Summary of <strong>the month</strong></h4><br />
                                    </div>
									
                                        <div>
                                        	<div class="content">
                                                <div class="border">
													
													<center><img src="http://cswnnos15.corp.ds.fedex.com/images/charts/summaryOfTheMonth.png" alt="Percentage of screen and metadatas Graph of the month" height="330" width="800"/></center>
												<br /><br />
			                                    <div class="clear"></div>
                                </div> </div> </div>
												
												
												
												
												

												
												
                                                </div>

                                            </div>
                                            <div class="shadow"></div>
                                        </div>
                                        
                                    </li><!-- slide ends -->
                                </ul>
								</div><!-- end of carousel -->                            


								<div class="one-thirds column alpha">
                            	<div class="title-wrapper">
                                    <div class="section-title">
                                        <h4 class="title">Global Statistics <strong>per sites</strong></h4>
                                    </div>
                                    <span class="divider"></span>
                                    <div class="clear"></div>
                                </div>
                                <ul class="accordion" id="1">
<?php				$cpt = 0;				            
					foreach($variables['sites'] as $site){  
						$cpt++;
						if ($cpt == 1){$tag = ' first';}else{$tag = '';};
						echo '<li><div class="parent'.$tag.'"><h6><div class="accordion-caption"></div>Percentage of screen recording for '.$site->getName().'</h6></div><div class="tcontent">';

						echo '<br /><ul><li><b>Total number of calls (espx):</b> '.$site->getTotalEspx().'</li><li><b>Perc. of voice recording:</b> '.$site->getAudioPercentage().'%</li><li><b>Perc. of screen recording:</b> '.$site->getScreenPercentage().'%</li><li><b>Perc. of metadata:</b> '.$site->getMetadataPercentage().'%</li></ul>';
						// echo '<h6><b> Screen recording and metadata percentage history on one month </b></h6>';
						echo '<img src="http://cswnnos15.corp.ds.fedex.com/images/charts/', $site->getAcronym(), '_1month.png" alt="Percentage of screen and metadatas Graph" height="330" width="800"/>';
						echo '<br /><br /></div></li>';
					}
?>

                                </ul>
							</div>
