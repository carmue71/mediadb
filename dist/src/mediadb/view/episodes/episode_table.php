							<li class="list-group-item list-group-item-action flex-column align-items-start transparent">
                            	<div class="d-flex w-100 justify-content-between">
                            		<h5 class='mb-1'>
                            			<a href='<?php print INDEX;?>showepisode?id=<?php print $set->ID_Episode;?>'><?php print $set->Title;?> </a>
											<?php if ( $set->isWatched() ) {?>
													&nbsp; <i class='fas fa-check-circle' style='color:Gold;'></i>
      										<?php } else {?>
      											&nbsp; <i class='far fa-circle' style='color:Grey;'></i>
      										<?php }?>
										</h5>
									<small>(<a href='<?php print INDEX;?>showchannel?id=<?php print $set->REF_Channel;?>'> <?php print $set->Channel;?> </a>)
										<br><?php $set->printRating(); ?>
									</small>
								</div>
								<p class="mb-1"><?php print nl2br(cutStr($set->Description, 120)) ?></p>
							</li>