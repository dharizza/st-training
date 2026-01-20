<?php

declare(strict_types=1);

namespace Drupal\download_files\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Provides a Download files form.
 */
final class DownloadFilesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'download_files_download_files';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['media'] = [
      '#type' => 'select',
      '#title' => $this->t('Select a file to download'),
      '#options' => $this->getFilesOptions(),
    ];

    $form['pass_phrase'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#description' => $this->t('Enter your email address to retrieve the file.'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ],
    ];

    $this->getFilesOptions();

    return $form;
  }

  /**
   * Get file options to show in the select list.
   */
  public function getFilesOptions() {
    // Use database abstraction layer for getting managed files.
    // $results = \Drupal::database()
    //   ->select('file_managed', 'f')
    //   ->fields('f', ['filename', 'uri'])
    //   ->condition('f.status', 1)
    //   ->execute()
    //   ->fetchAll();

    // $options = [];
    // foreach ($results as $file) {
    //   $options[$file->uri] = $file->filename;
    // }

    // Use Entity Queries to get list of files.
    $results = \Drupal::entityQuery('file')
      ->condition('status', 1)
      ->accessCheck()
      ->execute();

    $files = File::loadMultiple($results);

    $options = [];
    foreach ($files as $file) {
      $options[$file->getFileUri()] = $file->getFilename();
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('<front>');
  }

}
