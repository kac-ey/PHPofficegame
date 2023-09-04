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

    # print direction column from table
    public function getDirections($conn)
    {
        $stmt = "SELECT direction FROM " . $this->currTable;
        $stmt = $conn->prepare($stmt);
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

    # assign new room and new table based on user input
    public function getNextRoom($conn, $direction)
    {
        $stmt = "SELECT destination, newtable FROM " . $this->currTable . " WHERE direction=?;";
        $stmt = $conn->prepare($stmt);
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
                    return true;
                }
            }
        }
        else
        {
            return $result;
        }
    }

    # check and add item to inventory
    public function getItem()
    {
        # if all items found, go to success screen
        if(count($this->inventory) == count($this->items)-2)
        {
            return 1;
        }
        # else if in room with an item
        elseif($this->currRoom != 'the parking lot') {
            # get item in room
            $result = array_search($this->currRoom, $this->items);
            # if item is Toby, go to fail screen
            if($result == 'Toby'){
                return -1;
            }
            # else if item in inventory, print repeat visit
            elseif(in_array($result, $this->inventory))
            {
                return 2;
            }
        }
    }

    # add item to inventory
    public function addItem()
    {
        $result = array_search($this->currRoom, $this->items);
        array_push($this->inventory, $result);
    }


    # print info with inventory
    public function printStatement($conn)
    {
        echo $this->story, "<br><br><br>";
        $this->addItem();
        echo "You found your ", array_search($this->currRoom, $this->items), " in ", $this->currRoom, "!";
        echo "<br><br>Your inventory:<br>";
        echo implode(", ",$this->inventory);
        echo "<br><br>Which direction would you like to go?";
        $this->getDirections($conn);
    }

    public function printRepeat($conn)
    {
        echo $this->story, "<br><br><br>";
        echo "You've already been to ", $this->currRoom, ". There are no items left.";
        echo "<br><br>Your inventory:<br>";
        echo implode(", ",$this->inventory);
        echo "<br><br>Which direction would you like to go?";
        $this->getDirections($conn);
    }

    # print info with no inventory
    public function start($conn)
    {
        echo $this->story, "<br><br>";
        echo "<br>You are in $this->currRoom.";
        echo "<br>Which direction would you like to go?";
        $this->getDirections($conn);
    }

    # print info with error message
    public function noMove($conn)
    {
        echo $this->story, "<br><br>";
        echo "You entered an invalid direction. Try again.";
        echo "<br><br>You are in $this->currRoom.";
        echo "<br><br>Your inventory:<br>";
        echo implode(", ",$this->inventory);
        echo "<br><br>Which direction would you like to go?";
        $this->getDirections($conn);
    }
}