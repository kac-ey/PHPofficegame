<?php
session_start();
class Game
{
public $story;
public $success;
public $fail;
public $items;
public $currTable;

public $currRoom;

public $inventory = array();
public function __construct($story, $success, $fail, array $items, $currTable, $currRoom)
{
    $this->story = $story;
    $this->success = $success;
    $this->fail = $fail;
    $this->items = $items;
    $this->currTable = $currTable;
    $this->currRoom = $currRoom;
}


    public function getDirections($conn)
    {
        $check = "SELECT direction FROM " . $this->currTable;
        $stmt = $conn->prepare($check);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result)
        {
            if ($result->num_rows)
            {
                while ($row = $result->fetch_assoc())
                {
                    echo " [" . $row["direction"] . "]";
                }
            }
        }
    }


    public function getNextRoom($conn, $direction)
    {
        $check = "SELECT destination, newtable FROM " . $this->currTable . " WHERE direction=?;";
        $stmt = $conn->prepare($check);
        $stmt->bind_param("s", $direction);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result)
        {
            if ($result->num_rows)
            {
                while ($row = $result->fetch_assoc())
                {
                    $this->currRoom = $row["destination"];
                    $this->currTable = $row["newtable"];
                }
            }
        }
    }


    public function getItem()
    {
        if(count($this->inventory) == count($this->items)-1)
        {
            return 1;
        }
        elseif($this->currRoom != 'the parking lot') {
            $result = array_search($this->currRoom, $this->items);

            if($result == 'Toby'){
                return -1;
            }
            elseif(!in_array($result, $this->inventory))
            {
                array_push($this->inventory, $result);
            }
        }
    }


    public function printStatement($conn)
    {
        if($this->getItem() == 'Toby'){
            echo $this->fail;
        }
        else{
            echo $this->story, "<br><br>";
            echo "<br> You are in $this->currRoom.";
            echo "<br> Which direction would you like to go?";
            $this->getDirections($conn);
            echo "<br><br>Your inventory: <br>";
            echo implode(", ",$this->inventory);
        }
    }

    public function start($conn)
    {
        session_unset();
        echo $this->story, "<br><br>";
        echo "<br> You are in $this->currRoom.";
        echo "<br> Which direction would you like to go?";
        $this->getDirections($conn);
    }
}