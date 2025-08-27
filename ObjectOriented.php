<?php
class Student {
    private string $name;
    private array $courses = [];
    private float $courseFee = 1450.0;
    public function __construct(string $name) {
        $this->name = $name;
    }


    public function addCourse(string $course): void {
        $this->courses[] = $course;
    }
    public function deleteCourse(string $course): void {
        $index = array_search($course, $this->courses);
        if ($index !== false) {
            unset($this->courses[$index]);
            $this->courses = array_values($this->courses);
        }
    }
    public function getTotalFee(): float {
        return count($this->courses) * $this->courseFee;
    }
    public function displayInfo(): void {
        echo "Name of Student: " . $this->name . "<br>";
        echo "Enrolled In Courses: <br>";
        foreach ($this->courses as $course) {
            echo "- " . $course . "<br>";
        }
        echo "Total enrollment fee: ₱ " . $this->getTotalFee() . "<br>";
    }
}

