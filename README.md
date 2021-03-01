![preview](https://user-images.githubusercontent.com/23043953/109567000-f666da80-7ae4-11eb-8277-08187eeb128d.png)

This is a mini project to practice PDO with OOP in php
how to insert a employee info to database then update the employee info and delete it from database
if you want to practice to you clone this repository and create a database with this info

**Create a database**

```sql
CREATE DATABASE php_pdo CHARACTER SET UTF8 COLLATE utf8_general_ci;
```

**Then create a table with this info**

```sql
CREATE TABLE employees (
    id int(10) unsigned not null auto_increment,
    name varchar(50) not null,
    age tinyint(2) not null,
    address varchar(100) not null,
    salary decimal(6,2) not null,
    tax decimal(3,2) not null,
)
```
