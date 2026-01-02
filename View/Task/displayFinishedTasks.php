<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/MVC/srcForTodoList/Public/style.css">
</head>
<body>

    <h1>Finished task</h1>
    <ul>
        <li><a href="/MVC/srcForTodoList/Public/index.php/"><button>Go back</button></a></li>
        <li><form action="/MVC/srcForTodoList/Public/index.php/deleteTasksPermanently" method="post"><button type="submit">Delete selected finished tasks</button></form></li>
        <li><form action="/MVC/srcForTodoList/Public/index.php/returnTasksAsUnfinished" method="post"><button type="submit">Return as unfinished task</button></form></li>
    </ul>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Created At</th>
                <th>Important Level</th>
            </tr>
        </thead>

        <tbody>
            <form action="/MVC/srcForTodoList/Public/index.php/updateTaskStatusFromArchive" method="post">

            <?php foreach($finishedTasks as $row): ?>
            <tr>
                <td>
                        <input 
                        type="checkbox"
                        name="uncompleted[]"
                        value=<?= $row['id']?>
                        <?= $row['taskStatus'] === 'Done' ? '' : 'checked'?>
                        onchange="this.form.submit()">
              
                </td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['taskStatus'] ?></td>
                <td><?= $row['dueDate'] ?></td>
                <td><?= $row['createdAt'] ?></td>
                <td><?= $row['importantLevel'] ?></td>
            </tr>
            <?php endforeach; ?>
            </form>
        </tbody>

    </table>
</body>
</html>