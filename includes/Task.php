<?php

namespace DataHandle;

require_once __DIR__.'/db.php';
use MongoDB;

class Task
{
    public static function addTask($task)
    {
        $task = $task['task'];
        $date_time = date('Y-m-d');

        global $client;
        $collection = $client->todo->tasks;

        $task = $collection->insertOne(
            array(
                'description' => $task,
                'status' => 0,
                'date' =>  $date_time
                )
        );
        
        if ($task->getInsertedCount() === 0) {

            header('Location: https://localhost/todoMongo/index.php?stato=ko');
            exit;
        }

        header('Location: https://localhost/todoMongo/index.php?stato=ok');
        exit;
    }

    public static function selectData()
    {
        global $client;
        $collection = $client->todo->tasks;
        $tasks = $collection->find();

        $results = array();
        $results = iterator_to_array($tasks);
        return $results;
    }

    public static function updateTask($values)
    {
        $task = $values['task'];
        $id = $values['id'];

        global $client;
        $collection = $client->todo->tasks;

        for ($i = 0; $i < count($task); $i++) {
            $update_task = $collection->updateOne(
                array(
                    '_id'  => new MongoDB\BSON\ObjectId($id[$i])),
                array(
                    '$set' => array(
                        'description' => $task[$i]
                    )
                )
                
                    );
            
            $results[$i] = $update_task->getModifiedCount();
        }


        if (!$results) {
            header('Location: https://localhost/todoMongo/index.php?stato=ko');
            exit;
        }

        header('Location: https://localhost/todoMongo/index.php?stato=ok');
        exit;
    }

    public static function changeTaskStatus($id, $status)
    {
        $status = intval($status);

        global $client;
        $collection = $client->todo->tasks;

        $status = $collection->updateOne(
            array(
                '_id'  => new MongoDB\BSON\ObjectId($id),
            ),
            array(
                '$set' => [
                    'status' => $status
                ]
            )
        );


        if ($status->getModifiedCount === 0) {
            header('Location: https://localhost/todoMongo/index.php?stato=ko');
            exit;
        }

        header('Location: https://localhost/todoMongo/index.php?stato=ok');
        exit;
    }



    public static function deleteTask($id = null)
    {
        global $client;
        $collection = $client->todo->tasks;

        if (isset($id)) {
            $task = $collection->deleteOne(
                array(
                    '_id'  => new MongoDB\BSON\ObjectId($id),
                ),
            );
            

            if ($task->getDeletedCount === 0) {

                header('Location: https://localhost/todoMongo/index.php?statocanc=ko');
                exit;
            }

            header('Location: https://localhost/todoMongo/index.php?statocanc=ok');
            exit;
        } else {
            global $client;
            $collection = $client->todo->tasks;
            
            $task = $collection->deleteMany([]);


            if ($task->getDeletedCount === 0) {

                header('Location: https://localhost/todoMongo/index.php?statocanc=ko');
                exit;
            }

            header('Location: https://localhost/todoMongo/index.php?statocanc=ok');
            exit;
        }
    }
}
