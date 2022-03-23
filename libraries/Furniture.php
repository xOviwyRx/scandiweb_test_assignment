<?php


/**
 * Description of Furniture
 *
 * @author xoviwyrx
 */
class Furniture extends Product{
    private float $width;
    private float $height;
    private float $length;
    
    public function getWidth(): float {
        return $this->width;
    }

    public function getHeight(): float {
        return $this->height;
    }

    public function getLength(): float {
        return $this->length;
    }
    public function setWidth(float $width): void {
        $this->width = $width;
    }

    public function setHeight(float $height): void {
        $this->height = $height;
    }

    public function setLength(float $length): void {
        $this->length = $length;
    }
    public function insertRecord(): void {
        return;
    }
    public function setSpecificAttributes($row): void {
        $this->height = (float)$row['height'];
        $this->width = (float)$row['width'];
        $this->length = (float)$row['length'];
//        $this->height =  filter_input(INPUT_POST, 'Height');
//        $this->width =  filter_input(INPUT_POST, 'Width');
//        $this->length =  filter_input(INPUT_POST, 'Length');
    }
    public function getSpecificAttributes(): string {
        return "Dimension: ".$this->height.'x'.$this->width.'x'.$this->length;
    }
    public function addProductToDB() {
        $db = new Database();
       
       $query1 = "INSERT INTO `Product` (`sku`, `name`, `price`, `height`, `width`, `length`, `type`) "
              . "VALUES ('$this->sku', '$this->name', '$this->price', '$this->height', '$this->width', '$this->length', 'Furniture');";
       $db->insert($query1);
    }


}
