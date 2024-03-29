<?php

namespace classes;


abstract class Product {

    protected $sku;
    protected $name;
    protected $price;
    protected $product_id;

    public function __construct(string $raw_name, string $raw_sku, string $price) {
        
        $name = trim($raw_name);
        $sku = trim($raw_sku);
        if (empty($name) || empty($sku) || empty($price)){
            throw new \Exception("Please, submit required data");
        }
        if (!$this->validNumberField($price, '/^[0-9]+(\.[0-9]{1,2})?$/')){
            throw new \Exception("Please, provide the data of indicated type");
        }

        $this->name = htmlspecialchars($name);
        $this->sku = htmlspecialchars($sku);
        $this->price = $price;
    }

    protected function validNumberField(string $number, string $pattern = '/^[0-9]+(\.[0-9]{1})?$/') : bool {
        if (preg_match($pattern, $number)){
                return true;
            }
        return false;
    }

    public function getSku(): string {
        return $this->sku;
    }

    public function setProductId(int $product_id): void {
        $this->product_id = $product_id;
    }

    public function getProductId(): int {
        return $this->product_id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): string {
        return $this->price;
    }

    abstract public function setSpecificAttributes($row): void;
    abstract public function getSpecificAttributes(): string;

    static protected function getQueryAllRecords(): string{
        $query = "SELECT sku, Product.name, price, spec_attributes, Type.name as type, Product.product_id
                  FROM Product
                  INNER JOIN ProductType ON
                  Product.product_id = ProductType.product_id
                  INNER JOIN Type ON
                  ProductType.type_id = Type.type_id
                  ORDER BY Product.product_id
                  ";
        return $query;
    }

    static public function getAllProductsFromDB($db): array{
        $query = Product::getQueryAllRecords();
        $records = $db->select($query);
        $products = array();
        foreach ($records as $row){
           $name = $row['name'];
           $sku = $row['sku'];
           $price = $row['price'];
           $product_id = $row['product_id'];
           $class_name = "classes\\${row['type']}";
           $spec_attributes = json_decode($row['spec_attributes'], true);
           $product = new $class_name($name, $sku, $price);
           $product->setSpecificAttributes($spec_attributes);
           $product->setProductId($product_id);
           $products[] = $product;
        }
        return $products;
    }

    static public function deleteCheckedProducts($db, $checked_products){
        if (!empty($checked_products)){
                    foreach ($checked_products as $product_id){
                        Product::deleteProductById($db, $product_id);
                    }
                    header("Location: index.php");
                }
    }
    
    static private function deleteProductById($db, $product_id){
        $query1 = "DELETE FROM `ProductType`"
                . "WHERE product_id = $product_id;";
        $db->do_query($query1);

        $query2 = "DELETE FROM `Product`"
                . "WHERE product_id = $product_id;";
        $db->do_query($query2);
    }
    
    abstract protected function getSpecificAttributesInJSON() : string;

    public function getClassName():string{
        $path = explode('\\', static::class);
        return array_pop($path);
    }
    private function getTypeId($db):int{
       $query_select = "SELECT type_id FROM Type WHERE name = '{$this->getClassName()}';";
       return $db->select($query_select)->fetch_row()[0];
    }

    static public function saveProduct($db, $rows){

        if (!empty($rows['typeswitcher'])){
            $typeswitcher = $rows['typeswitcher'];
            $name = $rows['name'];
            $sku = $rows['sku'];
            $price = $rows['price'];

            try
            {
                $type = "classes\\$typeswitcher";
                $product = new $type($name, $sku, $price);
                $product->setSpecificAttributes($rows);
                $product->addProductToDB($db);
                echo json_encode(['code'=>'200', 'msg'=>'success']);
            }
            catch(\Exception $e){
                echo json_encode(['code'=>'500', 'msg'=>$e->getMessage()]);
            }
        }
        else{
           echo json_encode(['code'=>'404', 'msg'=>'Please, submit required data']);
        }
    }
    
    private function addProductToDB($db){
        $spec_attributes = $this->getSpecificAttributesInJSON();
        $product_id = $db->addNewProductToDB($this->sku, $this->name, $this->price, $spec_attributes);
        $type_id = $this->getTypeId($db);
        $query_insert = "INSERT INTO `ProductType` (`product_id`, `type_id`) "
                       . "VALUES ('$product_id', '$type_id');";
        $db->do_query($query_insert);
    }

}