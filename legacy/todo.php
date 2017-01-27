<?php

include 'config.php';
include 'header.php';

$id = $request->attributes->get('id');

$result = $db->query('SELECT * FROM todo WHERE id = '. $id);
$todo = $result->fetch(\PDO::FETCH_ASSOC);

?>

<p>
    <strong>Id</strong>: <?php echo $todo['id'] ?><br/>
    <strong>Title</strong>: <?php echo $todo['title'] ?><br/>
    <strong>Status</strong>: <?php echo $todo['is_done'] ? 'done' : 'not finished' ?>
</p>

<?php include 'footer.php' ?>