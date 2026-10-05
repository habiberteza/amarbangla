<?php

namespace App\Libraries;

class CI4Cart
{
    public function contents()
    {
        return session()->get('cart_contents', []);
    }

    public function insert($items = [])
    {
        $cart = session()->get('cart_contents', []);
        $cart[] = $items;
        session()->put('cart_contents', $cart);
        return true;
    }

    public function destroy()
    {
        session()->forget('cart_contents');
    }
}
