<?php

require('db.php');
require('employee.php');

    if (isset($_POST['submit'])) {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $salary = filter_input(INPUT_POST, 'salary', FILTER_SANITIZE_NUMBER_FLOAT	, FILTER_FLAG_ALLOW_FRACTION);
        $tax = filter_input(INPUT_POST, 'tax', FILTER_SANITIZE_NUMBER_FLOAT	, FILTER_FLAG_ALLOW_FRACTION);
        
        //Inserting or updating employees to database
        $sql = 'INSERT INTO employee SET name = "'. $name .'", address = "'. $address .'", age = "'. $age.'", salary = "'. $salary.'", tax = "'. $tax .'"';

        if ($connection->exec($sql)) {
            $message = 'Employee ' . $name . ' has inserted succesfuly';
        } else {
            $error = true;
            $message = 'Error inserting employee';
        }

    }

    // Reading from database
    $sql = 'SELECT * FROM employee';
    $stmt = $connection->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Employee');
    $result = (is_array($result) && !empty($result) ? $result : false);
    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>PDO by example</title>
</head>
<body>

    <div class="wrapper">
        <div class="set_info">
            <h3>Set employee info</h3>
            <form action="" method="POST" autocomplete="off">
                
                <?php
                    if (isset($message)) { ?>
                        <p class='message <?= isset($error) ? $error : ''?>'><?= $message ?></p>
                <?php  } ?>                 

                <label for="name">Employee name: </label>
                <input type="text" name="name" id="name" placeholder="Write the employee name here" required>

                <label for="age">Employee age: </label>
                <input type="number" name="age" id="age" placeholder="Write the employee age here" min="20" max="60" required>
                
                <label for="address">Employee address: </label>
                <input type="text" name="address" id="address" placeholder="Write the employee address here" required>

                <label for="salary">Employee Salary: </label>
                <input type="number" name="salary" id="salary" step="0.01" min="1500" max="9000" placeholder="Write the employee salary here" required>

                <label for="tax">Employee Tax (%): </label>
                <input type="number" name="tax" id="tax" placeholder="Write the employee tex here" step="0.01" min="1" max="5" required>
            
                <input type="submit" name="submit" value="Save">

            </form>
        </div>
        <div class="get_info">
            <table>
            <caption>Get employee info</caption>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Salary</th>
                        <th>Tax</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    if ($result !== false) {
                        foreach ($result as $employee) {
                            ?>
                                <tr>
                                    <td><?= $employee->name ?></td>
                                    <td><?= $employee->age ?></td>
                                    <td><?= $employee->address ?></td>
                                    <td><?= $employee->salary ?>$</td>
                                    <td><?= $employee->tax ?></td>
                                </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <td colspan="5">There's no employee to list</td>
                        <?php
                    }
                ?>
                    
                </tbody>
            </table>
        </div>
    </div>
    
</body>
</html>