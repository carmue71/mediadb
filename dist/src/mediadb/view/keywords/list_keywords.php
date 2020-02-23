<?php
/* list_keywors.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Lists the found keywords in alphabetical order
 */


include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
$keywords = $params['keywords'];
?>

<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<a id="Top"><br /></a> <a href=##>#</a> | <a href=#a>A</a> | <a
			href=#b>B</a> | <a href=#c>C</a> | <a href=#d>D</a> | <a href=#e>E</a>
		| <a href=#f>F</a> | <a href=#g>G</a> | <a href=#h>H</a> | <a href=#i>I</a>
		| <a href=#j>J</a> | <a href=#k>K</a> | <a href=#l>L</a> | <a href=#m>M</a>
		| <a href=#n>N</a> | <a href=#o>O</a> | <a href=#p>P</a> | <a href=#q>Q</a>
		| <a href=#r>R</a> | <a href=#s>S</a> | <a href=#t>T</a> | <a href=#u>U</a>
		| <a href=#v>V</a> | <a href=#w>W</a> | <a href=#x>X</a> | <a href=#y>Y</a>
		| <a href=#z>Z</a> |
		<h1>List of Keywords</h1>
	</div>
	<div class="col-12">
		<p align=right>
			<a href='#Top'>Top</a>
		</p>
		<h3>
			<a id='#'>#</a>
		</h3>
		<ul>
    
<?php

$firstLetter = "";
foreach ($keywords as $keyword) :
    if ($firstLetter != $keyword->key[0]) {
        $firstLetter = $keyword->key[0];
        if ($firstLetter >= 'a') {
            ?>
            </ul>
		<p align=right>
			<a href='#Top'>Top</a>
		</p>
		<h3>
			<a id='<?php print $firstLetter;?>'><?php print $firstLetter;?></a>
		</h3>
		<ul><?php
        }
    }
    ?>
    	<li><a href='showkeyword?key=<?php print $keyword->key;?>'><?php print $keyword->key;?> </a>
				<span class='badge badge-secondary'> (<?php print $keyword->getCount();?>)</span></li>
    		<?php endforeach;?>
    
    </ul>
	</div>
</div>


</div><!-- container -->

<?php include VIEWPATH.'fragment/js.php'; ?>
</body>
</html>