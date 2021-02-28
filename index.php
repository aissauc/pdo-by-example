<?php
session_start();

require('db.php');
require('employee.php');

    if (isset($_POST['submit'])) {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $salary = filter_input(INPUT_POST, 'salary', FILTER_SANITIZE_NUMBER_FLOAT	, FILTER_FLAG_ALLOW_FRACTION);
        $tax = filter_input(INPUT_POST, 'tax', FILTER_SANITIZE_NUMBER_FLOAT	, FILTER_FLAG_ALLOW_FRACTION);
        
        // bindParams
        $bindParams = array(
            ':name'     => $name,
            ':age'      => $age,
            ':address'  => $address,
            ':salary'   => $salary,
            ':tax'      => $tax
        );


        //Inserting or updating employees to database
        if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $sql = 'UPDATE employee SET name = :name, age = :age, address = :address, salary = :salary, tax = :tax WHERE id = :id';
            $bindParams['id'] = $id;
        } else {
            $sql = 'INSERT INTO employee SET name = :name, age = :age, address = :address, salary = :salary, tax = :tax';
        }

        $stmt = $connection->prepare($sql);
        if ($stmt->execute($bindParams) === true) {
            $_SESSION['message'] = 'Employee ' . $name . ' has saved succesfuly';
            header('location: http://mydomain.test');
            session_write_close();
            exit();
            
        } else {
            $error = true;
            $_SESSION['message'] = 'Error saving employee';

        }


    }

    // Update emplyee info
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id > 0) {
            $sql = 'SELECT * FROM employee WHERE id = :id';
            $stmt = $connection->prepare($sql);
            $foundUser = $stmt->execute(array(':id' => $id));
            if ($foundUser === true) {
                $user = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Employee', array('name', 'age', 'address', 'salary', 'tax'));
                $user = array_shift($user);
            }
        }
    }
    // Delete employee info
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($id > 0) {
            $sql = 'DELETE FROM employee WHERE id = :id';
            $stmt = $connection->prepare($sql);
            $foundUser = $stmt->execute(array(':id' => $id));
            if ($foundUser === true) {
                $_SESSION['message'] = 'Employee has deleted succesfuly';
                header('location: http://mydomain.test');
                session_write_close();
                exit();
            }
        }
    }


    // Reading from database
    $sql = 'SELECT * FROM employee';
    $stmt = $connection->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Employee', array('name', 'age', 'address', 'salary', 'tax'));
    $result = (is_array($result) && !empty($result) ? $result : false);
    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <title>PDO by example</title>
</head>
<body>

    <div class="wrapper">
        <div class="set_info">
            <h3>Set employee info</h3>
            <form action="" method="POST" autocomplete="off">
                
                <?php
                    if (isset($_SESSION['message'])) { ?>
                        <p class='message <?= isset($error) ? $error : ''?>'><?= $_SESSION['message']; ?></p>
                <?php  } 
                    unset($_SESSION['message']);
                ?>                 

                <label for="name">Employee name: </label>
                <input type="text" name="name" id="name" placeholder="Write the employee name here" value="<?= isset($user) ? $user->name : '' ?>" required>

                <label for="age">Employee age: </label>
                <input type="number" name="age" id="age" placeholder="Write the employee age here" min="20" max="60" value="<?= isset($user) ? $user->age : '' ?>" required>
                
                <label for="address">Employee address: </label>
                <input type="text" name="address" id="address" placeholder="Write the employee address here" value="<?= isset($user) ? $user->address : '' ?>" required>

                <label for="salary">Employee Salary: </label>
                <input type="number" name="salary" id="salary" step="0.01" min="1500" max="9000" placeholder="Write the employee salary here" value="<?= isset($user) ? $user->salary : '' ?>" required>

                <label for="tax">Employee Tax (%): </label>
                <input type="number" name="tax" id="tax" placeholder="Write the employee tex here" step="0.01" min="1" max="5" value="<?= isset($user) ? $user->tax : '' ?>" required>
            
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
                        <th>Controller</th>
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
                                    <td><?= round($employee->totalSalary()) ?>$</td>
                                    <td><?= $employee->tax ?></td>
                                    <td>
                                        <a href="/?action=edit&id=<?= $employee->id; ?>"><i class="fa fa-edit fa-lg"></i></a>
                                        <a href="/?action=delete&id=<?= $employee->id; ?>" onclick="if(!confirm('Do you want to delete this employee ?')) return false;"><i class="fa fa-trash fa-lg"></i></a>
                                    </td>
                                </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <td colspan="6">There's no employee to list</td>
                        <?php
                    }
                ?>
                    
                </tbody>
            </table>
        </div>
    </div>
    
</body>
</html>