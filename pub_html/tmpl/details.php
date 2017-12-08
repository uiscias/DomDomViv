</div>
	<div class="callout-hr"></div>                        
		<div class="container">
			<div class="clear"></div>
        </div>
		<div>
        <div class="content">
			<div class="border">
				<h2>Summary of <?php echo $variables['site']->getName(); ?></h2>
						      <table class="globalStyats">
                    <tr>
                        <th>Percentage of screen recording</th>
                        <th>Percentage of Audio recording</th>
                        <th>Total EREC in TwoStage</th>
                        <th>Total Old EREC in TwoStage</th>
                        <th>Missing Audio (espx at 0Ko)</th>
						<th>Metadatas percentage</th>
                    </tr>                    
					
			<?php //print_r($variables); 
			
				echo '<tr><td>', $variables['site']->getScreenPercentage(), ' % </td><td>', $variables['site']->getAudioPercentage(), ' % </td><td>', $variables['site']->getErecTwoStage(), '</td><td>', $variables['site']->getOldErecInTwoStageLocation(), '</td><td>', $variables['site']->getTotalMissingVoice(), '</td><td>', $variables['site']->getMetadataPercentage(),' % </td></tr>';
			
			
			?>
							</table><br /><br /><hr><br /><br />



			</div>
		</div>
	<div class="shadow"></div>
</div>


								<div class="one-thirds column alpha">
                            	<div class="title-wrapper">
                                    <div class="section-title">
                                        <h4 class="title">Details information</h4>
                                    </div>
                                    <span class="divider"></span>
                                    <div class="clear"></div>
                                </div>
                                <ul class="accordion" id="1">
                                    <li>
                                        <div class="parent first">
                                            <h6><div class="accordion-caption"></div>Missing screens by agent - realtime datas</h6>
                                        </div>
                                        <div class="tcontent">

														<table class="globalStyats">
                    <tr>
                        <th>Agent Firstname</th>
                        <th>Agent Lastname</th>
                        <th>EspxFile</th>
                        <th>ErecFile</th>
                        <th>erecStatus</th>
                        <th>startTime</th>
                    </tr>             							
					
			<?php 
			
				//print_r($variables['missingScreen']);
				foreach($variables['missingScreen'] as $missScreen){
					foreach($missScreen as $missScreenByAgent){
						$dateFormated = date_format($missScreenByAgent['startTime'], 'd-m-Y H:m');
						echo '<tr><td>', $missScreenByAgent['firstName'], '</td><td>', $missScreenByAgent['lastName'], '</td><td>', $missScreenByAgent['espxFile'], '</td><td>', $missScreenByAgent['erecFile'], '</td><td>', $missScreenByAgent['erecStatus'], '</td><td>', $dateFormated, '</td></tr>';
					}
				
				}
			
			?>
			
				</table>

				
                                        </div>
                                    </li>
                                   <li>
                                        <div class="parent">
                                            <h6><div class="accordion-caption"></div>Historical statistics - missing screens by agent by date</h6>
                                        </div>
                                        <div class="tcontent">
                       <?php 

						$dayTab;
						echo '<table class="globalStyats"><tr><th>Agent ID</th>';

						for ($day = 6; $day--; $day >= 0){
							$dateStart = (date("Ymd", time() - ((23.99 * 60 * 60) * ($day +1) )));
							$dateEnd = date("Ymd", time() - ((23.99 * 60 * 60) * $day));
							$dateString = date("d-m-Y", time() - ((23.99 * 60 * 60) * $day )) ;
							$dayTab[$day] = $dateString;
							echo '<th>', $dateString, '</th>';
						}
						echo '</tr>';
						
												
												
							foreach ($variables['missingScreenByAgentHistoric'] as $agent){
							
								echo '<tr>';
								echo '<td><center>', $agent[0]['agent'], '</center></td>';

								for ($day = 6; $day--; $day >= 0){
									$dateString = date("d-m-Y", time() - ((23.99 * 60 * 60) * $day )) ;
									foreach ($agent as $agentSummary){
										if ($agentSummary['date_of_check_formated'] == $dayTab[$day]){
											echo '<td align="center"><center>';
											echo $agentSummary['missing'], ' / ', $agentSummary['total'];
											echo '</center></td>';									
										}
		
										//print_r($res); echo '<br><br>';
									}
								}
								echo '</tr>';
								
							}
						
	
						
						?>
					
							</table><br /><br /><hr><br /><br />
                                        </div>
                                    </li>								
							
                                    <li>
                                        <div class="parent">
                                            <h6><div class="accordion-caption"></div>Graph representation on 1 month  </h6>
                                        </div>
                                        <div class="tcontent">
											
						<?php
							echo '<img src="http://cswnnos15.corp.ds.fedex.com/images/charts/', $variables['site']->getAcronym(), '_1month.png" alt="Percentage of screen and metadatas Graph" height="330" width="800"/>';

						?>
											
                                        </div>
                                    </li>
                                    <li>
                                        <div class="parent">
                                            <h6><div class="accordion-caption"></div>Graph representation on 3 month</h6>
                                        </div>
                                        <div class="tcontent">
                                       						<?php
							echo '<img src="http://cswnnos15.corp.ds.fedex.com/images/charts/', $variables['site']->getAcronym(), '_3month.png" alt="Percentage of screen and metadatas Graph" height="330" width="800"/>';

						?>
                                        </div>
                                    </li>
                                </ul>
							</div>
