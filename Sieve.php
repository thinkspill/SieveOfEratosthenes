<?php

/**
 * Class Sieve of Eratosthenes
 */
class Sieve
{
    private $debug = false;
    private $range = array();
    private $primes = array(2);
    private $n = 0;
    private $div_ops = 0;
    private $mark_ops = 0;
    private $mult_ops = 0;
    private $time = 0.0;
    private $start = 0.0;
    private $end = 0.0;
    private $fill_start = 0.0;
    private $fill_end = 0.0;
    private $fill_time = 0.0;

    public function __construct($n)
    {
        $this->n = $n;
    }

    /**
     * creates an array of n size
     * @param $n
     */
    private function create_range($n)
    {
        $this->range = array_fill(2, $n - 1, 1);
    }


    /**
     * marks all composite numbers of a given prime
     *
     * @param $prime int number for which to mark composites
     */
    private function mark_composites($prime)
    {
        foreach ($this->range as $number => $is_prime) {
            $this->mult_ops++;
            $composite = $prime * $number;
            if ($composite > $this->n) {
                $this->debug_message(
                    $composite . " is beyond " . $this->n . ", aborting"
                );
                return;
            }
            if ($this->range[$composite] == 0) {
                $this->debug_message(
                    $composite . " is already marked, skipping"
                );
                continue;
            }
            $this->debug_message(
                "Marking " . $composite . " composite of " . $prime
            );
            $this->mark_ops++;
            $this->range[$composite] = 0;
        }

        //        $range = count($this->range)-1;
        //        for ($i = 0; $i < $range; $i++)
        //        {
        //            if ($this->[$])
        //        }

    }

    /**
     * @param $m int check if this number is a prime or not
     * @return bool
     */
    private function is_prime($m)
    {
        $is = true;
        $sq = (int)floor(sqrt($m));
        $this->debug_message("SQ is " . $sq);
        for ($i = $sq; $i > 3; $i--) {

            if ($this->range[$i] == 0)
            {
                continue;
            }

            $this->debug_message("Trying $m % $i");

            $this->div_ops++;

            if ($m % $i == 0) {
                $this->debug_message("$m is not prime");
                $is = false;
            }
        }

        if ($is) {
            if ($m != 2) {
                $this->primes[] = $m;
            }
        }
        return $is;
    }

    private function start_timing($fill = false)
    {
        $field_start = 'start';

        if ($fill) {
            $field_start = 'fill_start';
        }
        $this->$field_start = microtime(true);
    }

    private function end_timing($fill = false)
    {
        $field_start = 'start';
        $field_end = 'end';
        $field_time = 'time';
        if ($fill) {
            $field_start = 'fill_start';
            $field_end = 'fill_end';
            $field_time = 'fill_time';
        }
        $this->$field_end = microtime(true);
        $this->$field_time = round($this->$field_end - $this->$field_start, 4);
    }

    /**
     * @param $message string to be output
     */
    private function debug_message($message)
    {
        if (!$this->debug) {
            return;
        }
        echo $message . "\n";
    }

    public function report()
    {
        $this->run($this->n);
        $count_primes = count($this->primes);
        $pps = round(count($this->primes) / $this->time);
        //        print_r($this->range);
        //        print_r($this->primes);
        echo "\n";
        echo "Searching " . $this->n . " numbers.\n";
        echo "Found " . $count_primes . " primes in " . $this->time . " s\n";
//        echo $pps . " primes per second in " . $this->ops . " operations (" . round(
//                $this->ops / $this->n
//            ) . "*n + n ops)";
        echo "\nDivisions: " . $this->div_ops . " = " . round($this->div_ops / $this->n) . "*n";
        echo "\nMultiplications: " . $this->mult_ops . " = " . round($this->mult_ops / $this->n) . "*n";
        echo "\nMarks: " . $this->mark_ops  . " = " . round($this->mark_ops / $this->n) . "*n";
        echo "\nFill time: " . $this->fill_time;
        echo "\nMemory: " . number_format(memory_get_peak_usage());
        echo "\n";
        //        print_r($this->primes);

    }

    /**
     * @param $n
     */
    private function run($n)
    {
        $this->start_timing();
        $this->start_timing(true);
        $this->create_range($n);
        $this->end_timing(true);
        foreach ($this->range as $possible_prime => $is_prime) {
            //            $this->debug_message("Checking " . $possible_prime);
            if ($this->range[$possible_prime] == 0) {
                //                $this->debug_message("Already Composite " . $possible_prime);
                continue;
            }
            if ($this->is_prime($possible_prime)) {
                $this->debug_message("Prime " . $possible_prime);
                $this->mark_composites($possible_prime);
            }
        }
        $this->end_timing();
    }
}

//$s = new Sieve(200);
//$s->report();

//$s = new Sieve(100);
//$s->report();
//
//$s = new Sieve(1000);
//$s->report();
//
//$s = new Sieve(10000);
//$s->report();
//
$s = new Sieve(150000);
$s->report();

