<?php echo $title ;?>
<table style="width:100%">
  <tr>
    <th>name</th>
    <th>phone</th>
  </tr>
<?php for($i=0;$i<count($items);$i++){ ?>
  <tr>
    <th><?php echo $items[$i]['name']; ?></th>
    <th><?php echo $items[$i]['phone']; ?></th>
  </tr>
<?php } ?>
</table>