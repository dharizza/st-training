<?php

declare(strict_types=1);

namespace Drupal\amd_blocks;

/**
 * Service that provides text transformations.
 */
final class TextTransformations {

  /**
   * Reverse text received. Example: text to txet.
   */
  public function reverse($text): string {
    return strrev($text);
  }

  /**
   * Uppercase all text received. Example: text to TEXT.
   */
  public function uppercase($text): string {
    return strtoupper($text);
  }

  /**
   * Lowercase all the text received. TEXT to text.
   */
  public function lowercase($text): string {
    return strtolower($text);
  }

}
