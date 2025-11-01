<?php

namespace App\Views\Decorators;

use CodeIgniter\View\ViewDecoratorInterface;
use Config\Services;

/* 

@adibenc
https://codeigniter.com/user_guide/outgoing/view_decorators.html

*/
class BaseDecorator implements ViewDecoratorInterface
{
    public static function decorate(string $html): string{
        // Modify the output here
		// preson("wip use decorator");

        return $html;
    }
}
