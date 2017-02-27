<?php
    class Task
    {
        private $description;
        private $due_date;
        private $id;

        function __construct($description, $due_date, $id = null)
        {
            $this->description = $description;
            $this->id = $id;
            $this->due_date = $due_date;
        }


        function getDescription()
        {
            return $this->description;
        }

        function getId()
        {
            return $this->id;
        }

        function getCategoryId()
        {
            return $this->category_id;
        }

        function getDueDate()
        {
            return $this->due_date;
        }

        static function getAll()
        {
          $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks ORDER BY due_date;");
          $tasks = array();
          foreach($returned_tasks as $task) {
            $description = $task['description'];
            $due_date = $task['due_date'];
            $id = $task['id'];
            $category_id = $task['category_id'];
            $new_task = new Task($description, $id, $category_id, $due_date);
            array_push($tasks, $new_task);
          }
          return $tasks;
        }

        static function find($search_id)
        {
          $found_task = null;
          $tasks = Task::getAll();
          foreach($tasks as $task) {
            $task_id = $task->getId();
            if ($task_id == $search_id) {
              $found_task = $task;
            }
          }
          return $found_task;
        }

        function setDescription($new_description)
        {
          $this->description = (string) $new_description;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = $new_due_date;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO tasks (description, category_id, due_date) VALUES ('{$this->getDescription()}', {$this->getCategoryId()}, '{$this->getDueDate()}')");

            // NOTE {$this->getCategoryId()} does not have quotes because you are looking to return an integer !!!

            $this->id = $GLOBALS['DB']->lastInsertId();
        }


        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM tasks;");
        }

    }
?>
