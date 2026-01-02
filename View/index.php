<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="/MVC/srcForTodoList/Public/style.css">
</head>
<body>
    <h1>Welcome to my Todo List App</h1>
    <ul>
        <li><a href="/MVC/srcForTodoList/Public/index.php/addNewTask"><button>Add task</button></a></li>
        <li><form action="/MVC/srcForTodoList/Public/index.php/migrateFinishedTask" method="post"><button type="submit">Delete task</button></form></li>
        <li><a href="/MVC/srcForTodoList/Public/index.php/displayFinishedTasks"><button>Finished task</button></a></li>
        <li>
            <form action="/MVC/srcForTodoList/Public/index.php/" method="get">
                <label for="search">Search:
                    <input type="text"
                            name="search"
                            value="<?= htmlspecialchars($keyword ?? '') ?>">
                    <button type="submit">search</button>
                </label>
            </form>
        </li>
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
            <form action="/MVC/srcForTodoList/Public/index.php/updateTaskStatus" method="post">
            <?php foreach($data as $row): ?>
            <tr>
                <td>
                        <input 
                        type="checkbox"
                        name="completed[]"
                        value=<?= $row['id']?>
                        <?= $row['taskStatus'] === 'Done' ? 'checked' : ''?>
                        onchange="this.form.submit()">
              
                </td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['taskStatus'] ?></td>
                <?php

                    $currentDate = strtotime('today midnight');
                    $dueDate = strtotime($row['dueDate']) + 100000;

                    if($dueDate < $currentDate)
                    {
                        echo '<td style="color: red;">' . $row['dueDate'] . '</td>';
                    }
                    else
                    {
                        echo '<td>' . $row['dueDate'] . '</td>';
                    }
                ?>
                <td><?= $row['createdAt'] ?></td>
                <td><?= $row['importantLevel'] ?></td>
                <td>
                    <a href="/MVC/srcForTodoList/Public/index.php/updateTaskForm?id=<?=$row['id'] ?>">
                        <button type="button">Edit</button>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </form>
        </tbody>

    </table>
</body>
</html>