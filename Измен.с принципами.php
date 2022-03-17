
<?php
$n1 = readline();
$n2 = readline();
/*
if ($n1 > $n2) {
    $max = $n1;
} else {
    $max = $n2;
}*/ 

//DRY — Don’t repeat yourself (Не повторяй себя) 

$max = ($n1 > $n2) ? $n1 : $n2;

echo $max;
?>



<script>
switch (s) {
        case 1:
            res_sot += 'Сто ';
            break;
        case 2:
            res_sot += 'Двести ';
            break;
        case 3:
            res_sot += 'Триста ';
            break;
        case 4:
            res_sot += 'Четыреста ';
            break;
        case 5:
            res_sot += 'Пятьсот ';
            break;
        case 6:
            res_sot += 'Шестьсот ';
            break;
        case 7:
            res_sot += 'Семьсот ';
            break;
        case 8:
            res_sot += 'Восемьсот ';
            break;
        case 9:
            res_sot += 'Девятьсот ';
            break;
    }
    
    //KISS Keep it simple and straightforward (Простота кода превыше всего)
    if (s < 1 || s > 9) {
        let messageErr = ОШИБОЧКА;
        answerField.innerText = messageErr;
    }
    let res_sot = [
        "Сто", 
        "Двести", 
        "Триста", 
        "Четыреста", 
        "Пятьсот", 
        "Шестьсот",
        "Семьсот", 
        "Восемьсот", 
        "Девятьсот",
    ];
    return res_sot[s - 1];
</script>





<?php
// без SOLID, ниже исправленный код с применением SOLID

class Order {

	public $items = array();
	public $User;

	function __construct($Products, $User){
		$this->User = $User;
		foreach($Products as $prod){
			$this->items[] = $prod;
		}
	}

	public function addItem( $item ) {
		$this->items[] = $item;
	} 

    public function load($order) {/*...*/}
    public function save($order) {/*...*/}
    public function update($order) {/*...*/}
    public function delete( $order ) {/*...*/}
	public function deleteItem( $item ) {/*...*/}
	public function calculateDiscount() {/*...*/}
    public function calculateTotalSum() {/*...*/} 
}


class Product {

    private $currentOrder = null;
	public $id = -1;
	public $name = '';
	public $price = -1;
	public $discount;
	public function  __construct($id, $name, $price, $discount = 0){
		$this->id = $id;
		$this->name = $name;
		$this->price = $price;
		$this->discount = $discount;

		//echo $id ."	". $name."	". $price."<br>";
	}
		
    public function add2Basket ($item){
        if(is_null($this->currentOrder)){
			$this->currentOrder = new Order();
		}
		return $this->currentOrder->addItem($item);
    }

}


class ProductRepository {
	public $Products = array();
	public function load( $items ) {
		$this->Products = array();
		foreach($items->items as $Prod){
			$this->Products[] = new Product($Prod->id, $Prod->name, $Prod->price,  $Prod->discount );
		}

	} 

	public function getProductByID( $prodID ){
		foreach($this->Products as $Product) {
			if ($Product->id == $prodID){
				return $Product;
			}
		}
	}

}


class User {
	public $user_id = -1;
	public $login = '';
	public int $user_discount = 0;
	public $delivery_address = '';
	function __construct(int $new_user_id){
		global $link;
		$result = mysqli_query($link, "SELECT USER_ID, LOGIN, DISCOUNT, delivery_address FROM users_29m WHERE user_id='" . $new_user_id . "'");
		
		if ($result->num_rows < 1)
		{
			echo 'User with id = ' . $new_user_id . " not found<br>";
			exit;
		}
		$row = mysqli_fetch_assoc($result);

		$this->user_id = $row['USER_ID'];
		$this->login = $row['LOGIN'];
		$this->user_discount = $row['DISCOUNT'];
		$this->delivery_address = $row['delivery_address'];
		
	}
}

class Basket{
	public $goods_in_basket = array();
	public $basketOwner;
	function __construct($new_user){
		$this->basketOwner = $new_user;
	}
	public function addProduct($product, int $qty = 1) {
		$this->goods_in_basket[] = $product;
	}
	public function delProduct($product, int $qty = 1) {
		foreach($this->goods_in_basket as $prod){
			if ($product->id == $prod->id){
				$key = array_search($prod, $this->goods_in_basket);
				unset($this->goods_in_basket[$key]);
				break;
			}
		}

	}


}

// с принципом SOLID


class Order {

	public $items = array();
	public $User;

	function __construct($Products, $User){
		$this->User = $User;
		foreach($Products as $prod){
			$this->items[] = $prod;
		}
	}

	public function addItem( $item ) {
		$this->items[] = $item;
	} 

    public function deleteItem( $item ) {/*...*/}
    public function calculateDiscount() {/*...*/} 
    public function calculateTotalSum() {/*...*/}
	
}

interface IOrderSource {
    public function load( $orderID );
    public function save( $order );
    public function update( $order );
    public function delete( $order );
  }
  class MySQLOrderSource implements IOrderSource {
    public function load( $orderID ) {/*...*/} 
    public function save( $order ) {/*...*/} 
    public function update( $order ) {/*...*/} 
    public function delete( $order ) {/*...*/}
  } 
  class ApiOrderSource implements IOrderSource { 
    public function load( $orderID ) {/*...*/} 
    public function save( $order ) {/*...*/}
    public function update( $order ) {/*...*/}
    public function delete( $order ) {/*...*/} 
  } 
  class OrderRepository {
    private $source;
    public function __constructor( IOrderSource $source ) {
      $this->source = $source;
    }
    public function load( $orderID ) {
      return $this->source->load( $orderID );
    } 
      
    public function save($order) {/*...*/}
    public function update($order) {/*...*/}
    public function delete( $order ) {/*...*/} 
}


class Product {

    private $currentOrder = null;
	public $id = -1;
	public $name = '';
	public $price = -1;
	public $discount;
	public function  __construct($id, $name, $price, $discount = 0){
		$this->id = $id;
		$this->name = $name;
		$this->price = $price;
		$this->discount = $discount;

		//echo $id ."	". $name."	". $price."<br>";
	}
		
    public function add2Basket ($item){
        if(is_null($this->currentOrder)){
			$this->currentOrder = new Order();
		}
		return $this->currentOrder->addItem($item);
    }

}


class ProductRepository {
	public $Products = array();
	public function load( $items ) {
		$this->Products = array();
		foreach($items->items as $Prod){
			$this->Products[] = new Product($Prod->id, $Prod->name, $Prod->price,  $Prod->discount );
		}

	} 

	public function getProductByID( $prodID ){
		foreach($this->Products as $Product) {
			if ($Product->id == $prodID){
				return $Product;
			}
		}
	}
	public function save( $order ) {/*...*/} 
	public function update( $order ) {/*...*/}
	public function delete( $order ) {/*...*/}
	//public Products;
}


class User {
	public $user_id = -1;
	public $login = '';
	public int $user_discount = 0;
	public $delivery_address = '';
	function __construct(int $new_user_id){
		global $link;
		$result = mysqli_query($link, "SELECT USER_ID, LOGIN, DISCOUNT, delivery_address FROM users_29m WHERE user_id='" . $new_user_id . "'");
		
		if ($result->num_rows < 1)
		{
			echo 'User with id = ' . $new_user_id . " not found<br>";
			exit;
		}
		$row = mysqli_fetch_assoc($result);

		$this->user_id = $row['USER_ID'];
		$this->login = $row['LOGIN'];
		$this->user_discount = $row['DISCOUNT'];
		$this->delivery_address = $row['delivery_address'];
		
	}
}

class Basket{
	public $goods_in_basket = array();
	public $basketOwner;
	function __construct($new_user){
		$this->basketOwner = $new_user;
	}
	public function addProduct($product, int $qty = 1) {
		$this->goods_in_basket[] = $product;
	}
	public function delProduct($product, int $qty = 1) {
		foreach($this->goods_in_basket as $prod){
			if ($product->id == $prod->id){
				$key = array_search($prod, $this->goods_in_basket);
				unset($this->goods_in_basket[$key]);
				break;
			}
		}

	}
}


// Без Solid 

interface iVehicle {
				
    public function ride($speed);
}


class Car implements iVehicle {
    
    public function ride($speed) {
        echo 'Машина может ездить co скоростью ' . $speed . 'км/ч';
    }
    
    public $interiorСolor = 'red';
    public $elementsCar = 'Дворники';
    
    public function turnOnWipers(){
        echo $this->elementsCar . ' включены';
    }
    public function pushSignal(){
        echo 'Signal on';
    }
    
}

class Tank implements iVehicle {
    
    private $elementsTank = 'Прицел';

    public function shoot(){
        echo $this->elementsTank . ' готов!';
    }
    
    public function ride($speed) {
        echo 'Танк ездит co скоростью ' . $speed . 'км/ч';
    }
    
}

class SpecialMachine implements iVehicle {
    
    private $elementsSpecial = 'Ковш';
    
    public function controlBucket(){
        echo $this->elementsSpecial . ' работает';
    }
    
   public function ride($speed) {
        echo 'Спецтехника может ездить co скоростью ' . $speed . 'км/ч';
    }
}

$car = new Car;
$tank = new Tank;
$specialMachine = new SpecialMachine;

$ridingCar = 150;
$ridingTank = 20;
$ridingSpMach = 80;

$car->turnOnWipers();
echo PHP_EOL;


$car->ride($ridingCar);
echo PHP_EOL.PHP_EOL;

$tank->ride($ridingTank);
echo PHP_EOL;
$tank->shoot();
echo PHP_EOL.PHP_EOL;

$specialMachine->ride($ridingSpMach);
echo PHP_EOL;
$specialMachine->controlBucket();
 


// с принципом SOLID

interface iDrive {
    public function drive($speed);
}

interface iReverse {
    public function reverse();
}

interface iElement {
    public function element();
}


abstract class Vehicle implements iDrive, iReverse, iElement {
  
  public function drive($speed) {
  	echo $this->drive;
  }

  public function reverse() {
  	echo $this->reverse;
  }

  public function element() {
    echo $this->element;
  }
}

class Car extends vehicle {
    
    
    public function drive($speed) {
        echo 'Машина может ездить co скоростью ' . $speed . 'км/ч';
    }

	public $element = "Дворники";

    public function turnOnWipers(){
        echo $this->element . ' включены';
    }
}

class Tank extends vehicle {

public function drive($speed) {
    echo 'Танк ездит co скоростью ' . $speed . 'км/ч';
}
  public $element = 'Прицел';

  public function shoot(){
    echo $this->element . ' готов!';
    }
}

class SpecialMachine extends vehicle {
	    
    public $element = 'Ковш';
    
    public function controlBucket(){
        echo $this->element . ' работает';
    }
    
   public function drive($speed) {
        echo 'Спецтехника может ездить co скоростью ' . $speed . 'км/ч';
    }
}
	
	$car = new Car;
	$tank = new Tank;
	$specialMachine = new SpecialMachine;
	
    $ridingCar = 150;
    $ridingTank = 20;
    $ridingSpMach = 80;
    
    $car->turnOnWipers();
	echo PHP_EOL;
    $car->drive($ridingCar);
	echo PHP_EOL.PHP_EOL;

    $tank->drive($ridingTank);
	echo PHP_EOL;
	$tank->shoot();
	echo PHP_EOL.PHP_EOL;

    $specialMachine->drive($ridingSpMach);
	echo PHP_EOL;
    $specialMachine->controlBucket();
