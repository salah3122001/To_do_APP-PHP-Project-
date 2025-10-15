<?php

include_once __DIR__."\..\database\config.php";
include_once __DIR__."\..\database\operations.php";

class Task extends config implements operations{
    private $id;
    private $title;
    private $is_completed;
    private $user_id;
    private $created_at;
    


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of is_completed
     */
    public function getIsCompleted()
    {
        return $this->is_completed;
    }

    /**
     * Set the value of is_completed
     */
    public function setIsCompleted($is_completed): self
    {
        $this->is_completed = $is_completed;

        return $this;
    }

    /**
     * Get the value of user_id
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     */
    public function setUserId($user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
    public function create()
    {
        $query="INSERT into tasks (title,is_completed,user_id) VALUES ('$this->title','$this->is_completed','$this->user_id')";
        return $this->runDML($query);
    }
    public function read()
    {
        $query="SELECT * FROM tasks WHERE user_id='$this->id'";
        return $this->runDQL($query);
    }
    public function update()
    {
        $query="UPDATE tasks SET title='$this->title' , is_completed='$this->is_completed' WHERE id ='$this->id'";
        return $this->runDML($query);
    }
    public function delete()
    {
        $query="DELETE FROM tasks WHERE id='$this->id' AND user_id=$this->user_id";
        return $this->runDML($query);
    }
    public function checkexists(){
        $query="SELECT * FROM tasks WHERE title='$this->title' AND user_id='$this->user_id'";
        return $this->runDQL($query);
    }
    
}




?>