<?php

/**
 * Class Sieve of Eratosthenes
 */
class Sieve
{
    private $debug = false;
    private $range = array();
    private $primes = array(2 => 2);
    private $n = 0;
    private $div_ops = 0;
    private $mark_ops = 0;
    private $mult_ops = 0;
    private $range_read = 0;
    private $range_write = 0;
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

    public function report()
    {
        $this->run($this->n);
        $count_primes = count($this->primes);
        echo "\n";
        echo "\nSearching " . $this->n . " numbers";
        echo "\nFound " . $count_primes . " primes in " . $this->time . " s";
        echo "\nDivisions: " . $this->div_ops . " = " . round(
                $this->div_ops / $this->n,
                1
            ) . "*n";
        echo "\nMultiplications: " . $this->mult_ops . " = " . round(
                $this->mult_ops / $this->n
            ) . "*n";
        echo "\nRange Reads: " . $this->range_read . " = " . round(
                $this->range_read / $this->n
            ) . "*n";
        echo "\nRange Writes: " . $this->range_write . " = " . round(
                $this->range_write / $this->n
            ) . "*n";

        $total_ops = $this->range_write + $this->range_read + $this->div_ops +
            $this->mult_ops + $this->mark_ops;

        echo "\nTotal ops: " . $total_ops . " ( total ops " . ceil(
                $total_ops / $this->n
            ) . "*n )";
        echo "\nFill time: " . $this->fill_time;
        echo "\nMemory: " . number_format(
                memory_get_peak_usage()
            ) . " ( " . ceil(memory_get_peak_usage() / $this->n) . " bytes * n )";
        echo "\n";
        //        print_r($this->primes);

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
     * creates an array of n size
     * @param $n
     */
    private function create_range($n)
    {
        $this->range = array_fill(2, $n - 1, 1);
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

    /**
     * @param $m int check if this number is a prime or not
     * @return bool
     */
    private function is_prime($m)
    {
        $is = true;
        $sq = (int)floor(sqrt($m));
        $this->debug_message("SQ is " . $sq);
        foreach ($this->primes as $prime) {
            if ($prime > $sq) {
                break;
            }
            $this->debug_message("Trying $m % $prime");

            $this->div_ops++;

            if ($m % $prime == 0) {
                $this->debug_message("$m is not prime");
                $is = false;
                break;
            }
        }

        if ($is) {
            $this->primes[$m] = $m;
        }
        return $is;
    }

    /**
     * marks all composite numbers of a given prime
     *
     * @param $prime int number for which to mark composites
     */
    private function mark_composites($prime)
    {
        foreach ($this->range as $number => $is_prime) {
            $this->range_read++;
            $this->mult_ops++;
            $composite = $prime * $number;
            if ($composite > $this->n) {
                $this->debug_message(
                    $composite . " is beyond " . $this->n . ", aborting"
                );
                return;
            }
            $this->range_read++;
            if ($this->range[$composite] == 0) {
                $this->debug_message(
                    $composite . " is already marked, skipping"
                );
                continue;
            }
            $this->debug_message(
                "Marking " . $composite . " composite of " . $prime
            );
            $this->range_write++;
            $this->range[$composite] = 0;
        }
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
            $this->debug_message("Checking " . $possible_prime . " - $is_prime");
            if (!$is_prime) {
                $this->debug_message("Already Composite " . $possible_prime);
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

$s = new Sieve(20);
$s->report();
//
//$s = new Sieve(100);
//$s->report();
//
//$s = new Sieve(1000);
//$s->report();
//
//$s = new Sieve(10000);
//$s->report();
//
//$s = new Sieve(300000);
//$s->report();

