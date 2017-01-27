<?php

//ini_set('display_errors', 0);

include 'config.php';
include 'header.php';

if ($_POST['action'] == 'create')
{
    $query = 'INSERT INTO todo (title) VALUES(\''. $_POST['title'] .'\');';
    $db->query($query);

    header('Location: list.php');
}
else if ($_GET['action'] == 'close')
{
    $query = 'UPDATE todo SET is_done = 1 WHERE id = '. $_GET['id'];
    $db->query($query);

    header('Location: list.php');
}
else if ($_GET['action'] == 'delete')
{
    $query = 'DELETE FROM todo WHERE id = '. $_GET['id'];
    $db->query($query);

    header('Location: list.php');
}

?>
<form action="<?php echo $router->generate('home'); ?>" method="post">
    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" size="45"/>
        <input type="hidden" name="action" value="create"/>
        <button type="submit">send</button>
    </div>
</form>

<?php

$result = $db->query('SELECT COUNT(*) FROM todo');
$count  = current($result->fetch(\PDO::FETCH_ASSOC));

?>
<p>
    There are <strong><?php echo $count ?></strong> tasks.
</p>

<?php $result = $db->query('SELECT * FROM todo'); ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($result->fetchAll(\PDO::FETCH_ASSOC) as $todo) {

            echo '<tr>';
            echo '  <td class="center">'. $todo['id'] .'</td>';
            echo '  <td><a href="' . $router->generate('todo', ['id' => $todo['id']]).'">'. $todo['title'] .'</a></td>';
            echo '  <td class="center">';

            if ($todo['is_done']) {
                echo '<span class="done">done</span>';
            } else {
                echo '<a href="list.php?action=close&amp;id='. $todo['id'] .'">close</a>';
            }

            echo '  </td>';
            echo '  <td class="center"><a href="list.php?action=delete&amp;id='. $todo['id'] .'">delete</a></td>';
            echo '</tr>';
        }
     ?>
    </tbody>
</table>

<?php include 'footer.php' ?>
