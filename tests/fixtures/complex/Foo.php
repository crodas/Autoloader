<?php

namespace Autoloader\test\complex;

use fooInterface as anotherSillyName;

class Complex extends \Simple  implements anotherSillyName, xxxFooBar
{
}

