<?php

class Rectangle {
    private float $width;
    private float $height;

    public function __construct(float $width = 1, float $height = 1) {
        $this->width = $width;
        $this->height = $height;
    }
    public function getArea(): float {
        return $this->width * $this->height;
    }
    public function getPerimeter(): float {
        return 2 * ($this->width + $this->height);
    }
    public function getWidth(): float {
        return $this->width;
    }
    public function getHeight(): float {
        return $this->height;
    }
}

$area = $perimeter = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $width = isset($_POST['width']) ? (float)$_POST['width'] : 1;
    $height = isset($_POST['height']) ? (float)$_POST['height'] : 1;
    $rect = new Rectangle($width, $height);
    $area = $rect->getArea();
    $perimeter = $rect->getPerimeter();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Testing ground ko muna</title>
</head>
<body>
    <form method="post">
    Width: <input type="number" name="width" step="any" required value="<?php echo isset($_POST['width']) ? htmlspecialchars($_POST['width']) : 1; ?>"><br>
    Height: <input type="number" name="height" step="any" required value="<?php echo isset($_POST['height']) ? htmlspecialchars($_POST['height']) : 1; ?>"><br>
<input type="submit" value="Calculate">
    </form>
    <?php if ($area !== "" && $perimeter !== ""): ?>
    <p>Area: <?php echo $area; ?></p>
    <p>Perimeter: <?php echo $perimeter; ?></p>
<?php endif; ?>
</body>
</html>
