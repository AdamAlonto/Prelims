<?php
class QuadraticEquation {
    private float $a;
    private float $b;
    private float $c;

    public function __construct(float $a, float $b, float $c) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
    public function getA(): float {
        return $this->a;
    }
    public function getB(): float {
        return $this->b;
    }
    public function getC(): float {
        return $this->c;
    }
    public function getDiscriminant(): float {
        return ($this->b * $this->b) - (4 * $this->a * $this->c);
    }
    public function getRoot1(): ?float {
        $d = $this->getDiscriminant();
        if ($d < 0 || $this->a == 0) return null;
        return (-$this->b + sqrt($d)) / (2 * $this->a);
    }
    public function getRoot2(): ?float {
        $d = $this->getDiscriminant();
        if ($d < 0 || $this->a == 0) return null;
        return (-$this->b - sqrt($d)) / (2 * $this->a);
    }
}

$discriminant = $root1 = $root2 = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = isset($_POST['a']) ? (float)$_POST['a'] : 1;
    $b = isset($_POST['b']) ? (float)$_POST['b'] : 1;
    $c = isset($_POST['c']) ? (float)$_POST['c'] : 1;
    $eq = new QuadraticEquation($a, $b, $c);
    $discriminant = $eq->getDiscriminant();
    $root1 = $eq->getRoot1();
    $root2 = $eq->getRoot2();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>quadratic equation testing</title>
</head>
<body>
<form method="post">
    a: <input type="number" name="a" step="any" required value="<?php echo isset($_POST['a']) ? htmlspecialchars($_POST['a']) : 1; ?>"><br>
    b: <input type="number" name="b" step="any" required value="<?php echo isset($_POST['b']) ? htmlspecialchars($_POST['b']) : 1; ?>"><br>
    c: <input type="number" name="c" step="any" required value="<?php echo isset($_POST['c']) ? htmlspecialchars($_POST['c']) : 1; ?>"><br>
<input type="submit" value="Solve">
    </form>
<?php if ($discriminant !== ""): ?>
    <p>Discriminant: <?php echo $discriminant; ?></p>
    <p>Root 1: <?php echo ($root1 !== null) ? $root1 : "No real root"; ?></p>
    <p>Root 2: <?php echo ($root2 !== null) ? $root2 : "No real root"; ?></p>
    <?php endif;?>
</body>
</html>
