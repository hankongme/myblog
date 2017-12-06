<?php
/**
 * Created by PhpStorm.
 * User: Robot
 * Date: 17/9/20
 * Time: 下午4:17
 * Position: ShenZhen
 */

namespace Tests\Unit;


use App\Product;

class ProductTest extends \PHPUnit_Framework_TestCase
{

    /** @test * */
    public function a_product_has_name()
    {

        $product = new Product('111', 2000);
        $this->assertEquals('111', $product->name());
    }

    /** @test * */
    public function a_product_has_price()
    {
        $product = new Product('111', 2000);
        $this->assertEquals('2000', $product->price());
    }


    /** @test * */
    public function a_product_can_be_sold_with_discount()
    {
        $product = new Product('111', 2000);
        $product->setDiscount(8);
        $this->assertEquals('1600', $product->price());
    }

    

}
