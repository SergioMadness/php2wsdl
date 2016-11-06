<?php namespace PHP2WSDL;

class WSDLWrapper
{
    /**
     * Handlers
     *
     * @var array
     */
    private static $handlers = [];

    /**
     * Add handler
     *
     * @param string    $name
     * @param \Callback $handler
     *
     * @return $this
     */
    public static function addHandler($name, $handler)
    {
        self::$handlers[$name] = $handler;
    }

    /**
     * Remove handler
     *
     * @param string $name
     *
     * @return $this
     */
    public static function removeHandler($name)
    {
        unset(self::$handlers[$name]);
    }

    public function __call($name, $args)
    {
        $result = call_user_func_array(self::$handlers[$name], $args);;
        \Log::info($result);
        return $result;
    }
}