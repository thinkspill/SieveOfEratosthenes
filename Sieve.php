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
    private $ops = 0;
    private $time = 0.0;
    private $start = 0.0;
    private $end = 0.0;

    public function __construct($n)
    {
        $this->start_timing();
        $this->n = $n;
        $this->create_range($n);
        foreach ($this->range as $possible_prime => $is_prime) {
            //            $this->debug_message("Checking " . $possible_prime);
            if ($this->range[$possible_prime] == 0) {
                //                $this->debug_message("Skipping " . $possible_prime);
                continue;
            }
            if ($this->is_prime($possible_prime)) {
                //                $this->debug_message("Prime " . $possible_prime);
                $this->mark_composites($possible_prime);
            }
        }
        $this->end_timing();
    }

    /**
     * creates an array of n size
     * @param $n
     */
    private function create_range($n)
    {
        $i = 2;
        while (true) {
            if ($i == $n + 1) {
                return;
            }
            $this->ops++;
            $this->range[$i] = 1;
            $i++;
        }
    }

    /**
     * marks all composite numbers of a given prime
     *
     * @param $prime int number for which to mark composites
     */
    private function mark_composites($prime)
    {
        foreach ($this->range as $number => $is_prime) {
            if ($prime * $number > $this->n) {
                return;
            }
            //            $this->debug_message("Marking " . $prime * $number . " not prime");
            //            $this->ops++;
            $this->range[$prime * $number] = 0;
        }
    }

    /**
     * @param $m int check if this number is a prime or not
     * @return bool
     */
    private function is_prime($m)
    {
        $is = true;
        $sq = (int)floor(sqrt($m));
        for ($i = $sq; $i > 2; $i--) {
            //            $this->debug_message("Start from $sq, checking $i");
            if ($this->range[$i] == 0 || $m == $i) {
                //                $this->debug_message("Skipping $m % $i");
                continue;
            }
            //            $this->debug_message("Trying $m % $i");
            $this->ops++;

            if ($m % $i == 0) {
                //                $this->debug_message("$m is not prime");
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

    private function start_timing()
    {
        $this->start = microtime(true);
    }

    private function end_timing()
    {
        $this->end = microtime(true);
        $this->time = round($this->end - $this->start, 4);
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
        $count_primes = count($this->primes);
        $pps = round(count($this->primes) / $this->time);
        echo "\n";
        echo "Found " . $count_primes . " primes in " . $this->time . " s\n";
        echo $pps . " primes per second in " . $this->ops . " operations (" . round(
                $this->ops / $this->n
            ) . "*n + n ops)";
        echo "\n";

    }
}

$s = new Sieve(10);
$s->report();

$s = new Sieve(100);
$s->report();

$s = new Sieve(1000);
$s->report();

$s = new Sieve(10000);
$s->report();

$s = new Sieve(100000);
$s->report();

