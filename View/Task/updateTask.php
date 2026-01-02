<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Update task form</h1>

    <form action="/MVC/srcForTodoList/Public/index.php/updateTask" method="post">
        <input type="hidden" name="id" value=<?= $task['id'] ?>>
        <label for="title">Title: <input type="text" name="title" value=<?= $task['title'] ?>></label><br><br>
        <label for="dueDate">Due date: <input type="date" name="dueDate" value=<?= $task['dueDate'] ?>> </label><br><br>
        <label for="importantLevel">Important Level (1-5): <input type="number" min="1" max="5" step="1"  name="importantLevel" value=<?= $task['importantLevel'] ?>></label><br><br>
        <button type="submit">Submit</button>
    </form>
    <br>
    <a href="/MVC/srcForTodoList/Public/index.php/"><button>Go back</button></a>
</body>
</html>