<?php if ($totalRows_results > $epp) { ?>
<div class="pagination">
<?php

if ($page_number != 1) {
echo "<a href='contacts.php?page=$prev'>&laquo; Previous </a>";
}

if ($page_number == 1) {
echo "&laquo; Previous ";
}


$i = 1;
do {
if ($_GET['page'] != $i) {
echo "<a href='contacts.php?page=$i'>&nbsp;$i&nbsp;</a>";
}

if ($_GET['page'] == $i) {
echo "&nbsp;$i&nbsp;";
}


$i++;
} while ($i <= $total_pages);

if ($page_number != $total_pages) {
echo "<a href='contacts.php?page=$next'> Next &raquo;</a>";
}

if ($page_number == $total_pages) {
echo " Next &raquo;";
}

?>      
</div>
<?php } ?>
