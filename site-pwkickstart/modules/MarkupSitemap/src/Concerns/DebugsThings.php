<?php

/**
 * Debugging helpers.
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Concerns;

trait DebugsThings
{
  /**
   * Dump vars and die.
   *
   * @param  mixed  $mixed The vars to dump
   * @return void
   */
  protected function dd(): void
  {
    $this->dump(func_get_args()) && die;
  }

  /**
   * Dump vars.
   *
   * @param  mixed  $mixed The vars to dump
   * @return void
   */
  protected function dump(): void
  {
    $this->header();

    array_map(
      function ($mixed) { var_dump($mixed); },
      func_get_args()
    );
  }

  /**
   * Prepare the content-type header
   *
   * @return void
   */
  protected function header(): void
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
  protected function headerPrepared($header): bool
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
  protected function pd(): void
  {
    $this->printVars(func_get_args()) && die;
  }

  /**
   * Print vars.
   *
   * @param  mixed  $mixed The vars to print
   * @return void
   */
  protected function printVars(): void
  {
    $this->header();

    array_map(
      function ($mixed) { print_r($mixed); },
      func_get_args()
    );
  }

  /**
   * Log things to a dedicated log file.
   *
   * @param $content
   * @return void
   */
  protected function logme($content): void
  {
    if ($this->config->debug) {
      $this->log->save('markup-sitemap', $content);
    }
  }
}
