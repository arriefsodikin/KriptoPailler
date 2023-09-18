<?php
    class Paillier{
        private $p;
        private $q;
        private $n;
        private $nn;
        private $g;
        private $lambda;
        private $gMu;

        public function __CONSTRUCT($p,$q){
            $this->p = $p;
            $this->q = $q;
            $this->n = $this->p * $this->q ;
            $this->nn = $this->n * $this->n;
            $this->g = rand(20,150);
            if (gcd($this->g,$this->nn)==1){
                echo"<hr>";
                print("g is relatively prime to n*n");
            }
            else{
                print("WARNING: g is NOT relatively prime to n*n. Will not work!!!");}            
            $this->lambda = lcm($this->p-1,$this->q-1);
            $this->l = (gmp_powm($this->g, $this->lambda, $this->nn)-1)/$this->n;
            $this->gMu= invmod($this->l,$this->n);
        }
        public function Enkripsi($m){
            $this->r = rand(20,$this->n-1);
            return (gmp_powm($this->g,$m,$this->nn) * gmp_powm($this->r,$this->n,$this->nn)) % $this->nn;
        }
        public function Dekripsi($c){
            $this->lu = (gmp_powm($c,$this->lambda,$this->nn)-1)/ $this->n;
            return ($this->lu * $this->gMu) % $this->n;
        }
    }
    // FUNCTION GCD & LCM
    function lcm($m, $n) {
        if ($m == 0 || $n == 0) return 0;
        $r = ($m * $n) / gcd($m, $n);
        return abs($r);
    }
    
    function gcd($a, $b) {
        while ($b != 0) {
            $t = $b;
            $b = $a % $b;
            $a = $t;
        }
        return $a;
    }

    // FUNGSI INVERSE MODULOS
    function invmod($a,$n){
        if ($n < 0) $n = -$n;
        if ($a < 0) $a = $n - (-$a % $n);
	$t = 0; $nt = 1; $r = $n; $nr = $a % $n;
	while ($nr != 0) {
		$quot= intval($r/$nr);
		$tmp = $nt;  $nt = $t - $quot*$nt;  $t = $tmp;
		$tmp = $nr;  $nr = $r - $quot*$nr;  $r = $tmp;
	}
	if ($r > 1) return -1;
	if ($t < 0) $t += $n;
	return $t;
    }
?>
