$class = strtolower($class);
if (isset($classes[$class])) {
    @if($stats)
        $GLOBALS['call_{{$stats}}']++;
    @end
    @if (count($deps) > 0)
    if (!empty($deps[$class])) {
        foreach ($deps[$class] as $zclass) {
            @include("validate", array('var' => 'zclass'));
        }
    }
    @end
    @include("validate", array('var' => 'class'));
    return true;
}
