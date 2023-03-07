<?php

namespace classes;

class DVD extends Product{
    private $size;

    public function setSize($size): void {

        if (empty($size)){
            throw new \Exception("Please, submit required data");
        }

        if (!$this->validNumberField($size)){
            throw new \Exception("Please, provide the data of indicated type");
        }

        $this->size = (float)$size;
    }

    public function setSpecificAttributes(array $row): void {
        $this->setSize($row['size']);
    }

    public function getSpecificAttributes(): string {
        return "Size: $this->size MB";
    }

    protected function getSpecificAttributesInJSON(): string {
        return json_encode(['size' => $this->size]);
    }
}
