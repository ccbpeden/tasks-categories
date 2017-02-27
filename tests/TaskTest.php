<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";

    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class TaskTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function test_GetDescription()
        {
            // Arrange
                $description = "Laundry";
                $due_date = "2017-02-21";
                $test_task = new Task($description, $due_date);

            // Act
                $result = $test_task->getDescription();

            // Assert
                $this->assertEquals($description, $result);

        }

        function testSetDescription()
        {
            //Arrange
            $description = "Do dishes.";
            $due_date = "2017-02-21";
            $test_task = new Task($description, $due_date);

            //Act
            $test_task->setDescription("Drink coffee.");
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals("Drink coffee.", $result);
        }


        function test_getId()
        {
            //Arrange
            $description = "Wash the dog";
            $due_date = "2017-02-21";
            $id = 1;
            $test_task = new Task($description, $due_date, $id);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        // function test_getCategoryId()
        // {
        //     //Arrange
        //     $name = "Home stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Wash the dog";
        //     $due_date = "2017-02-21";
        //     $test_task = new Task($description, $id, $due_date);
        //     $test_task->save();
        //
        //     //Act
        //     $result = $test_task->getCategoryId();
        //
        //     //Assert
        //     $this->assertEquals(true, is_numeric($result));
        // }

        function test_save()
        {
            //Arrange
            $id = 1;
            $description = "Wash the dog";
            $due_date = "2017-02-21";
            $test_task = new Task($description, $due_date, $id);

            //Act
            $test_task->save();

            //Assert
            $result = Task::getAll();
            $this->assertEquals($test_task, $result[0]);
        }

        function test_getAll()
        {
            //Arrange

            $description = "Wash the dog";
            $due_date = "2017-02-21";
            $id = 1;
            $test_task = new Task($description, $due_date, $id);
            $test_task->save();


            $description2 = "Water the lawn";
            $due_date2 = "2017-02-21";
            $id2 = 2;
            $test_task2 = new Task($description2, $due_date2, $id2);
            $test_task2->save();

            //Act
            $result = Task::getAll();


            //Assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $description = "Wash the dog";
            $due_date = "2017-02-21";
            $id = 1;
            $test_task = new Task($description, $due_date, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $due_date2 = "2017-02-21";
            $id2 = 2;
            $test_task2 = new Task($description2, $due_date2, $id2);
            $test_task2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $description = "Wash the dog";
            $due_date = "2017-02-21";
            $id = 1;
            $test_task = new Task($description, $due_date, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $due_date2 = "2017-02-21";
            $id2 = 2;
            $test_task2 = new Task($description2, $due_date2, $id2);
            $test_task2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function test_sort()
        {
            $description = "Wash the dog";
            $due_date = "2017-02-21";
            $id = 1;
            $test_task = new Task($description, $due_date, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $due_date2 = "2017-01-21";
            $id2 = 2;
            $test_task2 = new Task($description2, $due_date2, $id2);
            $test_task2->save();

            $result = Task::getAll();


            $this->assertEquals([$test_task2, $test_task], $result);

        }

        function testUpdate()
        {
            //Arrange
            $description = "Wash the dog";
            $due_date = "2017-02-21";
            $id = 1;
            $test_task = new Task($description, $due_date, $id);
            $test_task->save();

            $new_description = "Impeach the President";
            $new_due_date = "2018-11-10";

            //Act
            $test_task->update($new_description, $new_due_date);

            //Assert
            $this->assertEquals("Impeach the President", $test_task->getDescription());
        }

        function testDeleteTask()
        {
            //Arrange
            $name = "Home stuff";
            $id = 3;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $due_date = "2017-02-21";
            $id = 1;
            $test_task = new Task($description, $due_date, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $due_date2 = "2017-01-21";
            $id2 = 2;
            $test_task2 = new Task($description2, $due_date2, $id2);
            $test_task2->save();


            //Act
            $test_task->addCategory($test_category);
            $test_task->delete();

            //Assert
            $this->assertEquals([$test_task2], $test_category->getTasks());
        }

        function testAddCategory()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "File reports";
            $due_date = "2017-02-21";
            $id2 = 2;
            $test_task = new Task($description, $due_date, $id2);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);

            //Assert
            $this->assertEquals($test_task->getCategories(), [$test_category]);
        }

        function testGetCategories()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $name2 = "Volunteer stuff";
            $id2 = 2;
            $test_category2 = new Category($name2, $id2);
            $test_category2->save();

            $description = "File reports";
            $due_date = "2017-02-21";
            $id3 = 3;
            $test_task = new Task($description, $due_date, $id3);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);
            $test_task->addCategory($test_category2);

            //Assert
            $this->assertEquals($test_task->getCategories(), [$test_category, $test_category2]);
        }


    }
?>
