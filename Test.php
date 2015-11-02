<?php

/**
 * Test
 * @author Sara Ferran <sferran@gmail.com>
*/

class Test {
    
    /**
    * 1. FizzBuzz
    * Write a PHP script that prints all integer values from 1 to 100.
    * For multiples of three output "Fizz" instead of the value and for the multiples of five output "Buzz".
    * Values which are multiples of both three and five should output as "FizzBuzz".
    */
    public function fizzBuzz () 
    {
        
        for ($i = 1; $i <= 100; $i++) {
            if ($i%3 == 0 && $i%5 == 0) {
                echo 'FizzBuzz';
            }
            
            if ($i%3 == 0) {
        		echo 'Fizz';
            }

            if ($i%5 == 0) {
        		echo 'Buzz';
        	}

            if ($i%3 != 0 && $i%5 != 0) {
        		echo $i;
        	}
	        echo PHP_EOL;         
        }
        
    }    
    

    /*
    * 2. 500 Element Array
    * Write a PHP script to generate a random array of 500 integers (values of 1 – 500 inclusive).
    * Randomly remove and discard an arbitary element from this newly generated array.
    * Write the code to efficiently determine the value of the missing element.
    * Explain your reasoning with comments.
    */
    public function randonArray500 () 
    {

        $array = range(1,500);
        shuffle($array);

        $elem = rand(1,500);
        unset($array[$elem]); 
        print_r($array);

        $missing = array_sum(range(1,500)) - array_sum($array);
        echo $missing . PHP_EOL;

    }




    /*
    * 3. Database Connectivity
    * Demonstrate with PHP how you would connect to a MySQL (InnoDB) database and query for all
    * records with the following fields: (name, age, job_title) from a table called 'exads_test'.
    * Also provide an example of how you would write a sanitised record to the same table.
    */
    public function dataConnectivity () 
    {
        try {
            
            $dbh = new PDO('mysql:host=localhost;dbname=exads', 'root', 'root', array( PDO::ATTR_PERSISTENT => false));
            $stmt = $dbh->prepare("SELECT name,age,job_title FROM exads_test");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            print_r($rows);
            unset($stmt);

            $name = "Sara";
            $age = 32;
            $title = "developer";

            $stmt = $dbh->prepare("INSERT INTO exads_test (name,age,job_title) VALUES (:name,:age,:job_title);");
            $dbh->bindParam(":name", $name, PDO::PARAM_STR);
            $dbh->bindParam(":age", $age, PDO::PARAM_INT);
            $dbh->bindParam(":job_title", $title, PDO::PARAM_STR);
            $stmt->execute();
            unset($stmt);

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    
    /**
    * 4. Date Calculation
    * The Irish National Lottery draw takes place twice weekly on a Wednesday and a Saturday at 8pm.
    * Write a function or class that calculates and returns the next valid draw date based on the current
    * date and time AND also on an optionally supplied date and time.
    */
    public function draws ($datetime = null) 
    {

        $nextDrawCurrentTime = $this->nextLotteryDraw ();
        if (!empty ($datetime)){ 
            $nextDrawOptional = $this->nextLotteryDraw ($datetime);
        } 

        print_r($nextDrawCurrentTime);

    }
    
    public function nextLotteryDraw ($datetime = null) 
    {
     

        $date = new DateTime ($datetime);
        $dateAt20 = clone $date;

        $wedDraw = clone $date;
        $satDraw = clone $date;

        $dateAt20->modify ('this day 20:00');        
        $modder = $date >= $dateAt20 ? 'next' : 'this';

        $wedDraw->modify ($modder . ' wednesday 20:00');
        $satDraw->modify ($modder . ' saturday 20:00');            

        return $wedDraw < $satDraw ? $wedDraw : $satDraw;    

    }



    
    /**
    *  5. A/B Testing
    * Exads would like to A/B test a number of promotional designs to see which provides the best
    * conversion rate.
    * Write a snippet of PHP code that redirects end users to the different designs based on the database
    * table below. Extend the database model as needed.
    */
    public function getUserDesign () 
    {
        // Get all designs
        $designs = $this->getDesigns ();
        
        // If we don't have designs
        if (count ($designs) == 0) 
            return "No designs to choose";
        
        // Get a rand value, we are using this value to choose a design
        $rand = rand(1, 100);
        $sum = 0;
        $chosenDesign = null;
        
        foreach ($designs as $design) {
            $sum += $design ['split_percent'];
            if ($sum >= $rand) {
                $chosenDesign = $design;
                break;
            }
        }
        
        return $design;

    }
    
    public function getDesigns () 
    {
        
        // Getting the different designs from a database, or from a file, or something like that
        // In this test we are just making an array and we are adding a new design to the exmple provided, just to show that 
        // we can have as much designs as we want
        
        $designs = array (        
            0 => array ('design_id' => 1, 'design_name' => 'Design 1', 'split_percent' => 40),
            1 => array ('design_id' => 2, 'design_name' => 'Design 2', 'split_percent' => 25),
            2 => array ('design_id' => 3, 'design_name' => 'Design 3', 'split_percent' => 25),
            3 => array ('design_id' => 4, 'design_name' => 'Design 4', 'split_percent' => 10)
        );
        
        return $designs;
        
    }
   
}

    $test = new Test();
    $test->fizzBuzz();
    $test->randonArray500();
    $test->dataConnectivity();
    $test->draws();
    $test->getUserDesign();
