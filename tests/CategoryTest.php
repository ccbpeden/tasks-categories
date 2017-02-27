<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Category.php";

    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CategoryTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Category::deleteAll();
            Task::deleteAll();
        }

        function test_getName()
        {
          //Arrange
          $name = "Work Stuff";
          $test_Category = new Category($name);

          //Act
          $result = $test_Category->getName();

          //Assert
          $this->assertEquals($name, $result);
        }

        function testSetName()
        {
            //Arrange
            $name = "Kitchen chores";
            $test_category = new Category($name);

            //Act
            $test_category->setName("Home chores");
            $result = $test_category->getName();

            //Assert
            $this->assertEquals("Home chores", $result);
        }

        function test_getId()
        {
            //Arrange
            $name = "Work Stuff";
            $id = 1;
            $test_Category = new Category($name, $id);

            //Act
            $result = $test_Category->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            //Arrange
            $name = "Work stuff";
            $test_Category = new Category($name);
            $test_Category->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals($test_Category, $result[0]);
        }

        function testUpdate()
        {
            // Arrange
            $name = "Work stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $new_name = "Home stuff";

            // Act
            $test_category->update($new_name);

            // Assert
            $this->assertEquals("Home stuff", $test_category->getName());
        }

        function testDelete()
        {
            // Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "File reports";
            $due_date = "2018-12-11";
            $id2 = 2;
            $test_task = new Task($description, $due_date, $id2);
            $test_task->save();

            // Act
            $test_category->addTask($test_task);
            $test_category->delete();

            // Assert
            $this->assertEquals([], $test_task->getCategories());
        }

        function test_getAll()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_Category = new Category($name);
            $test_Category->save();
            $name2 = "Home stuff";
            $id2 = 2;
            $test_Category2 = new Category($name2);
            $test_Category2->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals([$test_Category, $test_Category2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Wash the dog";
            $test_Category = new Category($name);
            $test_Category->save();
            $name2 = "Home stuff";
            $test_Category2 = new Category($name2);
            $test_Category2->save();

            //Act
            Category::deleteAll();

            //Assert
            $result = Category::getAll();
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Wash the dog";
            $id = 1;
            $test_Category = new Category($name);
            $test_Category->save();
            $name2 = "Home stuff";
            $id2 = 2;
            $test_Category2 = new Category($name2);
            $test_Category2->save();

            //Act
            $result = Category::find($test_Category->getId());

            //Assert
            $this->assertEquals($test_Category, $result);
        }

        function test_addTask()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "File reports";
            $due_date = "2016-12-25";
            $id2 = 2;
            $test_task = new Task($description, $due_date, $id2);
            $test_task->save();

            //Act
            $test_category->addTask($test_task);

            //Assert
            $this->assertEquals($test_category->getTasks(), [$test_task]);
        }


        function testGetTasks()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Email client";
            $due_date = "2017-02-21";
            $id2 = 2;
            $test_task = new Task($description, $due_date, $id2);
            $test_task->save();


            $description2 = "Meet with boss";
            $due_date2 = "2017-02-21";
            $id3 = 3;
            $test_task2 = new Task($description2, $due_date2, $id3);
            $test_task2->save();


            //Act
            $test_category->addTask($test_task);
            $test_category->addTask($test_task2);
            $result = $test_category->getTasks();


            //Assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }



        // function testDeleteCategoryTasks()
        // {
        //     // Arrange
        //     $name = "Work Stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Build Website";
        //     $category_id = $test_category->getId();
        //     $task_due_date = "2017-02-22";
        //     $test_task = new Task($description, $id, $category_id, $task_due_date);
        //     $test_task->save();
        //
        //     $test_category->delete();
        //
        //     $this->assertEquals([], Task::GetAll());
        // }
    }
?>
