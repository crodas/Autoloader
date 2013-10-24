#* // <?php
#* function validate($var)
if (is_array($__var__)) {
    if (!$__var__[1]($__var__[0], false)) {
        #* if ($stats) 
        $GLOBALS['load___stats__']++;
        #* end
        #* if ($relative)
        require __DIR__  . $classes[$__var__[0]];
        #* else
        require $classes[$__var__[0]];
        #* end
    }
} else if (!class_exists($__var__, false)) {
    #* if ($stats) 
    $GLOBALS['load___stats__']++;
    #* end
    #* if ($relative)
    require __DIR__  . $classes[$__var__];
    #* else
    require $classes[$__var__];
    #* end
}
#* end
