<?php

namespace Autoloader\test\complex;

use fooInterface_Traits as anotherSillyName,
    zzzfoobar as localXXX;

class Complex_Traits extends \Simple_Traits  implements anotherSillyName, xxxFooBar_Traits
{
    use \zzzfoobar, localXXX;
    use zzzfoobar;

}

