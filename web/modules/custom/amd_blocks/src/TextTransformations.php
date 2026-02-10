<?php

declare(strict_types=1);

namespace Drupal\amd_blocks;

use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Logger\LoggerChannel;

/**
 * Service that provides text transformations.
 */
final class TextTransformations {

  /**
   * Logger channel.
   * 
   * @var Drupal\Core\Logger\LoggerChannel
   */
  protected LoggerChannel $logger;

  /**
   * Construct an instance of TextTransformations
   * 
   * @param Drupal\Core\Logger\LoggerChannelFactory $loggerFactory
   *   Instance of service logger.factory
   */
  public function __construct(LoggerChannelFactory $loggerFactory) {
    $this->logger = $loggerFactory->get('amd_blocks');
  }

  /**
   * Reverse text received. Example: text to txet.
   */
  public function reverse($text): string {
    // \Drupal::logger('amd_blocks')->notice('The text ' . $text . ' was reversed.');
    $this->logger->notice('The text ' . $text . ' was reversed.');
    return strrev($text);
  }

  /**
   * Uppercase all text received. Example: text to TEXT.
   */
  public function uppercase($text): string {
    // \Drupal::logger('amd_blocks')->notice('The text ' . $text . ' was transformed to UPPERCASE.');
    $this->logger->notice('The text ' . $text . ' was transformed to UPPERCASE.');
    return strtoupper($text);
  }

  /**
   * Lowercase all the text received. TEXT to text.
   */
  public function lowercase($text): string {
    // \Drupal::logger('amd_blocks')->notice('The text ' . $text . ' was transformed to lowercase.');
    $this->logger->notice('The text ' . $text . ' was transformed to lowercase.');
    return strtolower($text);
  }

  /**
   * Title case all the text received. 'Example Text' to 'Example text'.
   */
  public function titleCase($text): string {
    $channel = \Drupal::logger('amd_blocks');
    $channel->notice('The text ' . $text . ' was transformed to TitleCase.');
    return ucfirst($text);
  }

}
