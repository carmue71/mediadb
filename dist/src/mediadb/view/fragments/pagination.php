<!-- Pagination -->
		
       <?php 
       
       $idstr=(isset($_GET['id']) )?"id={$_GET['id']}&":""; 
       
       $pages = array();
       if ( $lastpage <= MAXPAGECNT ){
           for ($i=1; $i <= $lastpage; $i++){
                $pages[]=$i;          
           }
       } elseif ( $this->page >= $lastpage-MAXPAGECNT ){
           for ($i=$lastpage-MAXPAGECNT; $i <= $lastpage; $i++){
               $pages[]=$i;
           }
       } else {
           $start = max(1, $this->page - ((MAXPAGECNT-1)/2));
           for ($i=$start; $i < $start+MAXPAGECNT; $i++){
               $pages[]=$i;
           }
       }    
       ?>
       <br/>
<nav aria-label="Page navigation abstract">
	<ul class="pagination justify-content-center">
		<li class="page-item"><a class="page-link" href='<?php print INDEX."{$this->currentView}?{$idstr}page=1"?>'
			tabindex="-1">First</a></li>
		<?php foreach ($pages as $page ):?>
			<li class="page-item"><a class="page-link" href='<?php print INDEX."{$this->currentView}?{$idstr}page={$page}"?>'>
				<?php print $page?></a>
			</li>
		<?php endforeach; ?>
		<li class="page-item"><a class="page-link" href='<?php print INDEX."{$this->currentView}?{$idstr}page={$lastpage}"?>'>Last</a></li>
	</ul>
</nav>

<!-- Pagination -->