if (
    ! $classes[${{$var}}][1]( ${{$var}}, false )
) {
    @if ($stats) 
    $GLOBALS['load_{{$stats}}']++;
    @end
    @if ($relative)
    require __DIR__  . $classes[${{$var}}][0];
    @else
    require $classes[${{$var}}][0];
    @end
}
