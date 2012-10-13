#* // <?php
#* function validate($var)
if (!class_exists($__var__, false) #* if ($hasInterface) *# && !interface_exists($__var__, false)#* end if ($hasTraits) *# && !trait_exists($__var__, false) #* end  *#) {
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
