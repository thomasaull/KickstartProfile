<?php

/**
 * Debug trait. Used to dump things
 * during debugging.
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Traits;

trait DebugTrait
{
    /**
     * Dump vars and die.
     *
     * @param  mixed  $mixed The vars to dump
     * @return void
     */
    protected function dd()
    {
        $this->dump(func_get_args()) && die;
    }

    /**
     * Dump vars.
     *
     * @param  mixed  $mixed The vars to dump
     * @return void
     */
    protected function dump()
    {
        $this->header();
        array_map(
            function ($mixed) {
                var_dump($mixed);
            },
            func_get_args()
        );
        return true;
    }

    /**
     * Prepare the content-type header
     *
     * @return void
     */
    protected function header()
    {
        $header = 'Content-Type: text/plain';
        if (!$this->headerPrepared($header)) {
            header($header);
            $this->timestamp = -microtime(true);
        }
    }

    /**
     * Determine if a header has been prepared.
     *
     * @param  $header
     * @return bool
     */
    protected function headerPrepared($header)
    {
        $header = trim($header, ': ');

        foreach (headers_list() as $listedHeader) {
            if (stripos($listedHeader, $header) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Print vars and die.
     *
     * @param  mixed  $mixed The vars to print
     * @return void
     */
    protected function pd()
    {
        $this->printVars(func_get_args()) && die;
    }

    /**
     * Print vars.
     *
     * @param  mixed  $mixed The vars to print
     * @return void
     */
    protected function printVars()
    {
        $this->header();
        array_map(
            function ($mixed) {
                print_r($mixed);
            },
            func_get_args()
        );

        return true;
    }
}
