<?php
/**
 * Created by PhpStorm.
 * User: Robot
 * Date: 17/9/20
 * Time: 下午5:37
 * Position: ShenZhen
 */

namespace Tests;


use App\Order;
use App\Product;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function an_order_cansists_products(){
        $order = new Order();
        $product = new Product('iWatch',2000);
        $product_two = new Product('iWatch2',3000);
        $order->add($product);
        $order->add($product_two);
        $this->assertCount(2,$order->products());
    }


    /** @test */
    public function we_can_get_total_cost_from_all_products_of_an_order(){
        $order = new Order();
        $product = new Product('iWatch',2000);
        $product_two = new Product('iWatch2',3000);
        $order->add($product);
        $order->add($product_two);
        $this->assertEquals(5000,$order->total());
    }
}
